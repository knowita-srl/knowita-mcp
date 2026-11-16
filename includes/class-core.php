<?php
/**
 * Knowità AI – logica comune a llms.txt, API REST, MCP e schema.org.
 */

if ( ! defined( 'ABSPATH' ) && ! defined( 'KNOWITA_AI_TEST' ) ) {
	exit;
}

class Knowita_AI_Core {

	/** @var array */
	private $d;

	/** Sinonimi per la ricerca dei servizi (termine cercato => parole aggiunte). */
	private $sinonimi = array(
		'ai'            => 'artificial intelligence llm agenti chatbot',
		'ia'            => 'artificial intelligence llm agenti chatbot',
		'intelligenza'  => 'artificial intelligence',
		'hr'            => 'persone personale organizzazione valutazione incentivazione',
		'risorse umane' => 'persone personale organizzazione',
		'esg'           => 'sostenibilita',
		'csrd'          => 'sostenibilita bilancio reportistica standard',
		'dnf'           => 'bilancio sostenibilita',
		'nis2'          => 'cybersecurity',
		'cyber'         => 'cybersecurity',
		'kpi'           => 'dashboard scorecard reporting',
		'bsc'           => 'balanced scorecard',
		'okr'           => 'obiettivi',
		'mbo'           => 'obiettivi valutazione incentivazione',
		'margini'       => 'marginalita contabilita analitica',
		'costi'         => 'contabilita analitica marginalita budget',
		'budget'        => 'budgeting',
		'piano'         => 'pianificazione strategico',
		'strategia'     => 'strategy strategico pianificazione',
		'vendite'       => 'commerciali lead generation crm',
		'commerciale'   => 'commerciali crm lead',
		'marketing'     => 'digital marketing seo lead',
		'dati'          => 'data business intelligence analytics',
		'bi'            => 'business intelligence',
		'erp'           => 'software selezione implementazioni',
		'software'      => 'software selezione implementazioni',
		'leader'        => 'leadership',
		'formazione'    => 'training',
		'finanziamenti' => 'finanza finanziate scouting',
		'finanza'       => 'finanziamenti economico finanziaria',
		'emissioni'     => 'carbon',
		'clima'         => 'clima aziendale',
		'cambiamento'   => 'change management',
	);

	public function __construct( array $data ) {
		$this->d = $data;
	}

	public function data() {
		return $this->d;
	}

	public function url( $path ) {
		if ( '' === $path || null === $path ) {
			return '';
		}
		if ( 0 === strpos( $path, 'http' ) ) {
			return $path;
		}
		return rtrim( $this->d['site'], '/' ) . $path;
	}

	// ------------------------------------------------------------------
	// Dati strutturati (usati da API e MCP)
	// ------------------------------------------------------------------

	public function profilo() {
		$p = $this->d['profilo'];
		$p['sito'] = $this->d['site'];
		foreach ( $p['pubblicazioni'] as &$pub ) {
			$pub['url'] = $this->url( $pub['url'] );
		}
		unset( $pub );
		$p['business_unit'] = array();
		foreach ( $this->d['business_unit'] as $bu ) {
			$p['business_unit'][] = array(
				'id'          => $bu['id'],
				'nome'        => $bu['nome'],
				'descrizione' => $bu['descrizione'],
				'url'         => $this->url( $bu['url'] ),
			);
		}
		$p['metodo'] = array(
			'nome'        => $this->d['metodo']['nome'],
			'definizione' => $this->d['metodo']['definizione'],
			'url'         => $this->url( $this->d['metodo']['url'] ),
		);
		return $p;
	}

	public function metodo( $pilastro = '' ) {
		$m = $this->d['metodo'];
		$m['url'] = $this->url( $m['url'] );
		if ( '' !== $pilastro ) {
			$key = $this->norm( $pilastro );
			$m['pilastri'] = array_values( array_filter( $m['pilastri'], function ( $p ) use ( $key ) {
				return $this->norm( $p['id'] ) === $key || false !== strpos( $this->norm( $p['nome'] ), $key );
			} ) );
		}
		// Collega i pilastri alle aree di servizio.
		foreach ( $m['pilastri'] as &$p ) {
			$p['aree_collegate'] = array();
			foreach ( $this->d['business_unit'] as $bu ) {
				foreach ( $bu['aree'] as $a ) {
					if ( $a['pilastro'] === $p['id'] ) {
						$p['aree_collegate'][] = $bu['nome'] . ' / ' . $a['nome'];
					}
				}
			}
		}
		unset( $p );
		return $m;
	}

	/** Elenco piatto di tutti i servizi. */
	public function servizi() {
		$out = array();
		foreach ( $this->d['business_unit'] as $bu ) {
			foreach ( $bu['aree'] as $a ) {
				foreach ( $a['servizi'] as $s ) {
					$out[] = array(
						'servizio'        => $s[0],
						'area'            => $a['nome'],
						'area_descrizione' => $a['descrizione'],
						'business_unit'   => $bu['nome'],
						'business_unit_id' => $bu['id'],
						'pilastro'        => $a['pilastro'],
						'url'             => $this->url( '' !== $s[1] ? $s[1] : ( '' !== $a['url'] ? $a['url'] : $bu['url'] ) ),
					);
				}
			}
		}
		return $out;
	}

	public function trova_servizi( $query = '', $business_unit = '', $limite = 8 ) {
		$all = $this->servizi();
		if ( '' !== $business_unit ) {
			$bu  = $this->norm( $business_unit );
			$all = array_values( array_filter( $all, function ( $s ) use ( $bu ) {
				return $this->norm( $s['business_unit_id'] ) === $bu || false !== strpos( $this->norm( $s['business_unit'] ), $bu );
			} ) );
		}
		$query = trim( (string) $query );
		if ( '' === $query ) {
			return array_slice( $all, 0, max( 1, (int) $limite ) * 10 );
		}
		$terms = $this->terms( $query );
		$scored = array();
		foreach ( $all as $s ) {
			$name = $this->norm( $s['servizio'] );
			$area = $this->norm( $s['area'] );
			$ctx  = $this->norm( $s['area_descrizione'] . ' ' . $s['pilastro'] );
			$score = 0;
			foreach ( $terms as $t ) {
				if ( strlen( $t ) < 2 ) {
					continue;
				}
				if ( preg_match( '/\b' . preg_quote( $t, '/' ) . '/', $name ) ) {
					$score += 3;
				} elseif ( false !== strpos( $name, $t ) ) {
					$score += 2;
				}
				if ( false !== strpos( $area, $t ) ) {
					$score += 2;
				} elseif ( false !== strpos( $ctx, $t ) ) {
					$score += 1;
				}
			}
			if ( $score > 0 ) {
				$s['pertinenza'] = $score;
				$scored[]        = $s;
			}
		}
		usort( $scored, function ( $a, $b ) {
			return $b['pertinenza'] - $a['pertinenza'];
		} );
		return array_slice( $scored, 0, max( 1, (int) $limite ) );
	}

	public function radar() {
		return $this->d['radar'];
	}

	public function contatti() {
		$p = $this->d['profilo'];
		return array(
			'ragione_sociale' => $p['ragione_sociale'],
			'email'           => $p['email'],
			'telefono'        => $p['telefono'],
			'sede'            => $p['sede']['indirizzo'] . ', ' . $p['sede']['cap'] . ' ' . $p['sede']['citta'] . ' (' . $p['sede']['provincia'] . ')',
			'pagina_contatti' => $this->url( '/contatti/' ),
			'sito'            => $this->d['site'],
		);
	}

	/** Ricerca nei contenuti del sito (solo in WordPress). Esclude le schede dei membri del Club. */
	public function cerca_contenuti( $query, $limite = 5 ) {
		if ( ! function_exists( 'get_posts' ) ) {
			return array();
		}
		$limite = min( 10, max( 1, (int) $limite ) );
		$types  = array_diff( array( 'post', 'page' ), $this->d['escludi_post_type'] );
		$posts  = get_posts( array(
			's'                => $query,
			'post_type'        => array_values( $types ),
			'post_status'      => 'publish',
			'posts_per_page'   => $limite * 4,
			'has_password'     => false,
			'suppress_filters' => false,
		) );
		$out = array();
		foreach ( $posts as $post ) {
			$link = get_permalink( $post );
			if ( $this->escluso( $link ) ) {
				continue;
			}
			$excerpt = has_excerpt( $post ) ? get_the_excerpt( $post ) : wp_trim_words( wp_strip_all_tags( strip_shortcodes( $post->post_content ) ), 45 );
			$out[]   = array(
				'titolo'  => html_entity_decode( get_the_title( $post ), ENT_QUOTES, 'UTF-8' ),
				'url'     => $link,
				'data'    => get_the_date( 'Y-m-d', $post ),
				'estratto' => html_entity_decode( $excerpt, ENT_QUOTES, 'UTF-8' ),
			);
			if ( count( $out ) >= $limite ) {
				break;
			}
		}
		return $out;
	}

	// ------------------------------------------------------------------
	// Eventi (calendario annuale) e News
	// ------------------------------------------------------------------

	public function calendario_url( $anno ) {
		return $this->url( sprintf( $this->d['eventi']['calendario_percorso'], (int) $anno ) );
	}

	/** Archivi annuali del calendario, dal più recente. */
	public function archivio_eventi( $anno_corrente ) {
		$out = array();
		for ( $a = (int) $anno_corrente; $a >= (int) $this->d['eventi']['archivio_dal']; $a-- ) {
			$out[] = array( 'anno' => $a, 'url' => $this->calendario_url( $a ) );
		}
		return $out;
	}

	/**
	 * Eventi letti dal calendario dell'anno in corso (con ripiego sull'anno precedente).
	 * @param string $periodo 'prossimi' | 'passati' | 'tutti'
	 */
	public function eventi( $periodo = 'tutti', $testo = '', $oggi = null ) {
		$oggi  = $oggi ? $oggi : ( function_exists( 'current_time' ) ? current_time( 'Y-m-d' ) : gmdate( 'Y-m-d' ) );
		$anno  = (int) substr( $oggi, 0, 4 );
		$lista = $this->eventi_anno( $anno );
		$anno_usato = $anno;
		if ( null === $lista ) { // calendario dell'anno non ancora pubblicato
			$anno_usato = $anno - 1;
			$lista      = $this->eventi_anno( $anno_usato );
		}
		$lista = is_array( $lista ) ? $lista : array();

		foreach ( $lista as &$e ) {
			$e['stato'] = ( '' !== $e['data'] && $e['data'] >= $oggi ) ? 'in programma' : ( '' === $e['data'] ? 'data non indicata' : 'realizzato' );
		}
		unset( $e );

		if ( 'prossimi' === $periodo ) {
			$lista = array_values( array_filter( $lista, function ( $e ) { return 'in programma' === $e['stato']; } ) );
			usort( $lista, function ( $a, $b ) { return strcmp( $a['data'], $b['data'] ); } );
		} elseif ( 'passati' === $periodo ) {
			$lista = array_values( array_filter( $lista, function ( $e ) { return 'realizzato' === $e['stato']; } ) );
		}

		$testo = trim( (string) $testo );
		if ( '' !== $testo ) {
			$terms = $this->terms( $testo );
			$lista = array_values( array_filter( $lista, function ( $e ) use ( $terms ) {
				$t = $this->norm( $e['titolo'] . ' ' . $e['formato'] );
				foreach ( $terms as $w ) {
					if ( strlen( $w ) > 2 && false !== strpos( $t, $w ) ) {
						return true;
					}
				}
				return false;
			} ) );
		}

		return array(
			'descrizione'  => $this->d['eventi']['descrizione'],
			'formati'      => $this->d['eventi']['formati'],
			'calendario'   => array( 'anno' => $anno_usato, 'url' => $this->calendario_url( $anno_usato ) ),
			'eventi'       => $lista,
			'temi_recenti' => $this->d['eventi']['temi_recenti'],
			'storia'       => $this->d['eventi']['storia'],
			'archivio'     => $this->archivio_eventi( $anno_usato - 1 ),
			'nota'         => 'Gli eventi sono organizzati ogni anno dal ' . $this->d['eventi']['archivio_dal'] . '. Per partecipare: ' . $this->d['profilo']['email'] . '.',
		);
	}

	/** @return array|null null se la pagina del calendario non esiste. */
	private function eventi_anno( $anno ) {
		$cache_key = 'knowita_ai_eventi_' . $anno;
		if ( function_exists( 'get_transient' ) ) {
			$c = get_transient( $cache_key );
			if ( false !== $c ) {
				return 'none' === $c ? null : $c;
			}
		}
		$html = $this->scarica( $this->calendario_url( $anno ) );
		$ev   = null === $html ? null : $this->estrai_eventi( $html, $anno );
		if ( function_exists( 'set_transient' ) ) {
			set_transient( $cache_key, null === $ev ? 'none' : $ev, null === $ev ? HOUR_IN_SECONDS : 6 * HOUR_IN_SECONDS );
		}
		return $ev;
	}

	private function scarica( $url ) {
		if ( ! function_exists( 'wp_remote_get' ) ) {
			return null;
		}
		// In locale (test) il sito non è www.knowita.it: usa l'home del WordPress corrente.
		if ( function_exists( 'home_url' ) ) {
			$url = str_replace( rtrim( $this->d['site'], '/' ), rtrim( home_url(), '/' ), $url );
		}
		$r = wp_remote_get( $url, array( 'timeout' => 10, 'redirection' => 3, 'headers' => array( 'User-Agent' => 'Knowita-AI/1.0 (+https://www.knowita.it/llms.txt)' ) ) );
		if ( is_wp_error( $r ) || 200 !== (int) wp_remote_retrieve_response_code( $r ) ) {
			return null;
		}
		return wp_remote_retrieve_body( $r );
	}

	/** Estrae gli eventi dall'HTML del calendario. Pubblica per i test. */
	public function estrai_eventi( $html, $anno ) {
		$mesi = array( 'gennaio' => 1, 'febbraio' => 2, 'marzo' => 3, 'aprile' => 4, 'maggio' => 5, 'giugno' => 6, 'luglio' => 7, 'agosto' => 8, 'settembre' => 9, 'ottobre' => 10, 'novembre' => 11, 'dicembre' => 12 );
		$host = strtolower( (string) parse_url( $this->d['site'], PHP_URL_HOST ) );
		$host_local = function_exists( 'home_url' ) ? strtolower( (string) parse_url( home_url(), PHP_URL_HOST ) ) : $host;

		// URL da non considerare eventi: menu, pagine note, sezioni escluse.
		$noti = array();
		foreach ( $this->d['pagine_chiave'] as $p ) { $noti[] = trim( $p[1], '/' ); }
		foreach ( $this->d['business_unit'] as $bu ) {
			$noti[] = trim( $bu['url'], '/' );
			foreach ( $bu['aree'] as $a ) { $noti[] = trim( $a['url'], '/' ); }
		}
		if ( function_exists( 'wp_get_nav_menus' ) ) {
			foreach ( wp_get_nav_menus() as $menu ) {
				foreach ( (array) wp_get_nav_menu_items( $menu ) as $item ) {
					$noti[] = trim( (string) parse_url( $item->url, PHP_URL_PATH ), '/' );
				}
			}
		}
		$noti = array_filter( array_unique( $noti ) );

		$prev = libxml_use_internal_errors( true );
		$dom  = new DOMDocument();
		$dom->loadHTML( '<?xml encoding="utf-8"?>' . $html );
		libxml_clear_errors();
		libxml_use_internal_errors( $prev );

		// Ignora header, footer e navigazione.
		$xp    = new DOMXPath( $dom );
		$links = $xp->query( '//a[@href][not(ancestor::header) and not(ancestor::footer) and not(ancestor::nav)]' );

		$out  = array();
		$visti = array();
		foreach ( $links as $a ) {
			$href = trim( $a->getAttribute( 'href' ) );
			$u    = parse_url( $href );
			if ( empty( $u['path'] ) ) {
				continue;
			}
			$h = strtolower( $u['host'] ?? $host );
			if ( $h !== $host && $h !== 'knowita.it' && $h !== $host_local ) {
				continue;
			}
			$path = trim( $u['path'], '/' );
			// Le schede evento stanno al primo livello del sito (es. /sessione-strategica-.../).
			if ( '' === $path || false !== strpos( $path, '/' ) || in_array( $path, $noti, true ) || isset( $visti[ $path ] ) ) {
				continue;
			}
			if ( preg_match( '/^(calendario|category|tag|wp-|feed|shop|contatti|about|content-hub|education|club|membri|leader)/', $path ) || $this->escluso( '/' . $path . '/' ) ) {
				continue;
			}

			// Blocco di testo che contiene solo questo evento.
			$blocco = $a;
			for ( $i = 0; $i < 6 && $blocco->parentNode instanceof DOMElement; $i++ ) {
				$parent = $blocco->parentNode;
				$n = 0;
				foreach ( $xp->query( './/a[@href]', $parent ) as $l ) {
					$lp = trim( (string) parse_url( $l->getAttribute( 'href' ), PHP_URL_PATH ), '/' );
					if ( '' !== $lp && $lp !== $path && false === strpos( $lp, '/' ) && ! in_array( $lp, $noti, true ) ) {
						$n++;
					}
				}
				if ( $n > 0 || strlen( $parent->textContent ) > 900 ) {
					break;
				}
				$blocco = $parent;
			}
			$parti = array();
			foreach ( $xp->query( './/text()', $blocco ) as $tn ) {
				$parti[] = $tn->nodeValue;
			}
			$testo = trim( preg_replace( '/\s+/u', ' ', implode( ' ', $parti ) ) );

			$titolo = trim( preg_replace( '/\s+/u', ' ', $a->textContent ) );
			if ( mb_strlen( $titolo ) < 15 || preg_match( '/^(scopri|leggi|iscriviti|dettagli|info|vai|partecipa|registrati)/i', $titolo ) ) {
				$titolo = $this->titolo_da_testo( $testo, $path );
			}

			$data = '';
			if ( preg_match( '/\b(\d{1,2})\s+(' . implode( '|', array_keys( $mesi ) ) . ')(?:\s+(\d{4}))?/iu', $testo, $m ) ) {
				$y    = ! empty( $m[3] ) ? (int) $m[3] : (int) $anno;
				$data = sprintf( '%04d-%02d-%02d', $y, $mesi[ mb_strtolower( $m[2] ) ], (int) $m[1] );
			}

			$visti[ $path ] = true;
			$out[] = array(
				'data'    => $data,
				'titolo'  => $titolo,
				'formato' => $this->formato_evento( $testo . ' ' . $path ),
				'url'     => rtrim( $this->d['site'], '/' ) . '/' . $path . '/',
			);
		}
		return $out;
	}

	private function titolo_da_testo( $testo, $path ) {
		if ( function_exists( 'url_to_postid' ) ) {
			$id = url_to_postid( home_url( '/' . $path . '/' ) );
			if ( $id ) {
				return html_entity_decode( get_the_title( $id ), ENT_QUOTES, 'UTF-8' );
			}
		}
		return ucfirst( str_replace( '-', ' ', $path ) );
	}

	private function formato_evento( $t ) {
		$t = $this->norm( $t );
		if ( false !== strpos( $t, 'masterclass' ) ) {
			return 'Masterclass';
		}
		if ( false !== strpos( $t, 'forum' ) ) {
			return 'Top Management Forum';
		}
		if ( preg_match( '/\b(milano|roma|torino|bologna|firenze|arezzo|padova|verona|brescia|bergamo|in presenza)\b/', $t ) ) {
			return 'Executive Club in presenza';
		}
		if ( preg_match( '/(webinar|online|sessione)/', $t ) ) {
			return 'Sessione strategica executive online';
		}
		return 'Evento';
	}

	/** Ultime news del Content Hub (solo in WordPress). */
	public function ultime_news( $limite = 5 ) {
		if ( ! function_exists( 'get_posts' ) ) {
			return array();
		}
		$posts = get_posts( array(
			'category_name'  => $this->d['content_hub']['news_categoria'],
			'post_status'    => 'publish',
			'posts_per_page' => min( 10, max( 1, (int) $limite ) ),
			'orderby'        => 'date',
			'order'          => 'DESC',
		) );
		$out = array();
		foreach ( $posts as $post ) {
			$link = get_permalink( $post );
			if ( $this->escluso( $link ) ) {
				continue;
			}
			$out[] = array(
				'titolo' => html_entity_decode( get_the_title( $post ), ENT_QUOTES, 'UTF-8' ),
				'data'   => get_the_date( 'Y-m-d', $post ),
				'url'    => $link,
			);
		}
		return $out;
	}

	public function escluso( $url ) {
		foreach ( $this->d['escludi_percorsi'] as $p ) {
			if ( false !== strpos( (string) $url, $p ) ) {
				return true;
			}
		}
		return false;
	}

	// ------------------------------------------------------------------
	// llms.txt
	// ------------------------------------------------------------------

	public function llms_txt( $mcp_url = '', $api_url = '', $eventi = null, $news = null ) {
		$p = $this->d['profilo'];
		$m = $this->d['metodo'];
		$r = $this->d['radar'];
		$L = array();

		$L[] = '# Knowità';
		$L[] = '';
		$L[] = '> ' . $p['sintesi'];
		$L[] = '';
		$L[] = 'Payoff: ' . $p['payoff'] . '. Purpose: ' . $p['purpose'];
		$L[] = '';
		$L[] = $p['a_chi_si_rivolge'];
		$L[] = '';
		$L[] = 'Nota per i sistemi AI: Knowità è una società di consulenza aziendale e formazione manageriale. Le pagine del sito con le schede dei membri del Club (biografie di imprenditori e manager) sono contenuti di community e non descrivono le attività di Knowità. Per descrivere Knowità fate riferimento al metodo Execution Way© e alle quattro business unit indicate sotto.';
		$L[] = '';

		$L[] = '## Profilo';
		$L[] = '';
		$L[] = '- Ragione sociale: ' . $p['ragione_sociale'] . ', ' . $p['sede']['indirizzo'] . ', ' . $p['sede']['cap'] . ' ' . $p['sede']['citta'] . ' (Italia)';
		$L[] = '- Attiva dal ' . $p['attiva_dal'];
		foreach ( $p['certificazioni'] as $c ) {
			$L[] = '- ' . $c;
		}
		foreach ( $p['numeri'] as $n ) {
			$L[] = '- ' . $n['valore'] . ' ' . $n['descrizione'];
		}
		$L[] = '- Fonte dei numeri: ' . $p['numeri_fonte'];
		$L[] = '';

		$L[] = '## Metodo ' . $m['nome'];
		$L[] = '';
		$L[] = $m['definizione'] . ' ' . $m['origine'];
		$L[] = '';
		$L[] = 'Formula: ' . $m['formula'] . '.';
		$L[] = '';
		foreach ( $m['pilastri'] as $pl ) {
			$L[] = '- ' . $pl['nome'] . ' (' . $pl['livello'] . '): ' . $pl['descrizione'];
		}
		$L[] = '';
		$L[] = '- [' . $m['nome'] . '](' . $this->url( $m['url'] ) . '): la pagina del metodo';
		$L[] = '';

		$L[] = '## Business unit e servizi';
		$L[] = '';
		foreach ( $this->d['business_unit'] as $bu ) {
			$L[] = '### ' . $bu['nome'];
			$L[] = '';
			$L[] = $bu['descrizione'];
			$L[] = '';
			foreach ( $bu['aree'] as $a ) {
				$nomi = array();
				foreach ( $a['servizi'] as $s ) {
					$nomi[] = $s[0];
				}
				$label = '' !== $a['url'] ? '[' . $a['nome'] . '](' . $this->url( $a['url'] ) . ')' : $a['nome'];
				$L[]   = '- ' . $label . ': ' . $a['descrizione'] . ' Servizi: ' . implode( '; ', $nomi ) . '.';
			}
			$L[] = '';
		}

		$L[] = '## ' . $r['nome'];
		$L[] = '';
		$L[] = $r['descrizione'] . ' ' . $r['unicita'];
		$L[] = '';
		$L[] = $r['famiglia'] . ' ' . $r['disponibilita'];
		$L[] = '';

		$ev  = $this->d['eventi'];
		$L[] = '## Eventi ed Executive Education';
		$L[] = '';
		$L[] = $ev['descrizione'] . ' Calendario annuale dal ' . $ev['archivio_dal'] . '.';
		$L[] = '';
		foreach ( $ev['formati'] as $f ) {
			$L[] = '- ' . $f['nome'] . ': ' . $f['descrizione'];
		}
		$L[] = '';
		$anno = function_exists( 'current_time' ) ? (int) current_time( 'Y' ) : (int) gmdate( 'Y' );
		$L[] = $ev['storia'];
		$L[] = '';
		$L[] = '- [Calendario eventi ' . $anno . '](' . $this->calendario_url( $anno ) . '): eventi in programma e realizzati';
		$L[] = '- [Archivio ' . ( $anno - 1 ) . '](' . $this->calendario_url( $anno - 1 ) . '): gli anni precedenti, dal ' . $ev['archivio_dal'] . ', seguono lo stesso indirizzo';
		$L[] = '';
		if ( is_array( $eventi ) && ! empty( $eventi ) ) {
			$L[] = 'Prossimi eventi:';
			$L[] = '';
			foreach ( array_slice( $eventi, 0, 5 ) as $e ) {
				$L[] = '- ' . ( $e['data'] ? $e['data'] . ', ' : '' ) . '[' . $e['titolo'] . '](' . $e['url'] . ') (' . $e['formato'] . ')';
			}
			$L[] = '';
		}
		$L[] = 'Temi trattati di recente: ' . implode( '; ', $ev['temi_recenti'] ) . '.';
		$L[] = '';

		$ch  = $this->d['content_hub'];
		$L[] = '## Content Hub';
		$L[] = '';
		$L[] = $ch['descrizione'];
		$L[] = '';
		$L[] = '- [News](' . $this->url( $ch['news_url'] ) . '): aggiornamenti e approfondimenti recenti';
		if ( is_array( $news ) ) {
			foreach ( array_slice( $news, 0, 5 ) as $n ) {
				$L[] = '  - ' . $n['data'] . ', [' . $n['titolo'] . '](' . $n['url'] . ')';
			}
		}
		foreach ( $ch['categorie'] as $c ) {
			if ( 'News' !== $c[0] ) {
				$L[] = '- [' . $c[0] . '](' . $this->url( $c[1] ) . ')';
			}
		}
		$L[] = '';
		$L[] = 'Alcuni casi di successo:';
		$L[] = '';
		foreach ( $ch['casi'] as $c ) {
			$L[] = '- [' . $c[0] . '](' . $this->url( $c[1] ) . ')';
		}
		$L[] = '';

		$L[] = '## Pagine di riferimento';
		$L[] = '';
		foreach ( $this->d['pagine_chiave'] as $pg ) {
			$L[] = '- [' . $pg[0] . '](' . $this->url( $pg[1] ) . '): ' . $pg[2];
		}
		$L[] = '';

		$L[] = '## Pubblicazioni';
		$L[] = '';
		foreach ( $p['pubblicazioni'] as $pub ) {
			$L[] = '- [' . $pub['titolo'] . ' (' . $pub['anno'] . ')](' . $this->url( $pub['url'] ) . ')';
		}
		$L[] = '';

		if ( $mcp_url || $api_url ) {
			$L[] = '## Accesso per agenti AI';
			$L[] = '';
			if ( $mcp_url ) {
				$L[] = '- [Server MCP Knowità](' . $mcp_url . '): server Model Context Protocol pubblico, in sola lettura (trasporto Streamable HTTP)';
			}
			if ( $api_url ) {
				$L[] = '- [API Knowità](' . $api_url . '): API REST JSON pubblica, in sola lettura';
			}
			$L[] = '';
		}

		$L[] = '## Optional';
		$L[] = '';
		$L[] = '- [Contatti](' . $this->url( '/contatti/' ) . '): ' . $p['email'] . ', ' . $p['telefono'];
		$L[] = '';

		return implode( "\n", $L );
	}

	// ------------------------------------------------------------------
	// schema.org JSON-LD
	// ------------------------------------------------------------------

	public function schema_org() {
		$p    = $this->d['profilo'];
		$site = rtrim( $this->d['site'], '/' );
		$catalogo = array();
		foreach ( $this->d['business_unit'] as $bu ) {
			$items = array();
			foreach ( $bu['aree'] as $a ) {
				$items[] = array(
					'@type'       => 'Offer',
					'itemOffered' => array(
						'@type'       => 'Service',
						'name'        => $a['nome'],
						'description' => $a['descrizione'],
						'url'         => $this->url( '' !== $a['url'] ? $a['url'] : $bu['url'] ),
						'provider'    => array( '@id' => $site . '/#knowita' ),
						'areaServed'  => 'IT',
					),
				);
			}
			$catalogo[] = array(
				'@type'           => 'OfferCatalog',
				'name'            => $bu['nome'],
				'description'     => $bu['descrizione'],
				'itemListElement' => $items,
			);
		}
		$knows = array( 'Strategy Execution', 'Esecuzione della strategia', 'Execution Way', 'Pianificazione strategica', 'Controllo di gestione', 'OKR', 'Budgeting', 'Contabilità analitica', 'Business Intelligence', 'Organizzazione aziendale', 'Sistemi di incentivazione', 'Sostenibilità ESG', 'Bilancio di sostenibilità', 'Digital Transformation', 'Intelligenza artificiale per le imprese', 'Leadership', 'Executive Education' );

		$org = array(
			'@context'      => 'https://schema.org',
			'@type'         => array( 'Organization', 'ProfessionalService' ),
			'@id'           => $site . '/#knowita',
			'name'          => $p['nome'],
			'legalName'     => $p['ragione_sociale'],
			'url'           => $site . '/',
			'slogan'        => $p['payoff'],
			'description'   => $p['sintesi'],
			'foundingDate'  => (string) $p['attiva_dal'],
			'vatID'         => 'IT' . $p['partita_iva'],
			'email'         => $p['email'],
			'telephone'     => $p['telefono'],
			'address'       => array(
				'@type'           => 'PostalAddress',
				'streetAddress'   => $p['sede']['indirizzo'],
				'postalCode'      => $p['sede']['cap'],
				'addressLocality' => $p['sede']['citta'],
				'addressRegion'   => $p['sede']['provincia'],
				'addressCountry'  => $p['sede']['paese'],
			),
			'areaServed'    => array( '@type' => 'Country', 'name' => 'Italia' ),
			'knowsAbout'    => $knows,
			'hasOfferCatalog' => array(
				'@type'           => 'OfferCatalog',
				'name'            => 'Servizi Knowità',
				'itemListElement' => $catalogo,
			),
			'hasCredential' => array(
				'@type'              => 'EducationalOccupationalCredential',
				'credentialCategory' => 'certification',
				'name'               => 'UNI EN ISO 9001:2015',
			),
		);
		if ( ! empty( $p['social'] ) ) {
			$org['sameAs'] = array_values( $p['social'] );
		}
		return $org;
	}

	// ------------------------------------------------------------------
	// Utilità
	// ------------------------------------------------------------------

	public function norm( $s ) {
		$s = mb_strtolower( (string) $s, 'UTF-8' );
		$s = strtr( $s, array( 'à' => 'a', 'á' => 'a', 'è' => 'e', 'é' => 'e', 'ì' => 'i', 'í' => 'i', 'ò' => 'o', 'ó' => 'o', 'ù' => 'u', 'ú' => 'u', '’' => "'", '©' => '' ) );
		return trim( preg_replace( '/[^a-z0-9\' ]+/', ' ', $s ) );
	}

	private function terms( $q ) {
		$q     = $this->norm( $q );
		$extra = array();
		foreach ( $this->sinonimi as $k => $v ) {
			if ( preg_match( '/\b' . preg_quote( $k, '/' ) . '\b/', $q ) ) {
				$extra[] = $v;
			}
		}
		$stop = array( 'di', 'del', 'della', 'delle', 'dei', 'per', 'con', 'una', 'uno', 'il', 'lo', 'la', 'le', 'gli', 'e', 'ed', 'in', 'a', 'da', 'che', 'come', 'nostra', 'nostro', 'azienda', 'aziendale', 'mia', 'mio', 'abbiamo', 'serve', 'vogliamo' );
		$words = preg_split( '/[\s\']+/', $q . ' ' . implode( ' ', $extra ) );
		$words = array_diff( array_filter( $words ), $stop );
		// Radice semplice: tronca i plurali/singolari italiani più comuni.
		$out = array();
		foreach ( $words as $w ) {
			$out[] = strlen( $w ) > 5 ? substr( $w, 0, -1 ) : $w;
		}
		return array_values( array_unique( $out ) );
	}
}
