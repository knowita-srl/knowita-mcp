<?php
/**
 * Knowità AI – server MCP (Model Context Protocol), trasporto Streamable HTTP senza stato.
 * Solo lettura, nessun dato personale, nessuna autenticazione.
 */

if ( ! defined( 'ABSPATH' ) && ! defined( 'KNOWITA_AI_TEST' ) ) {
	exit;
}

class Knowita_AI_MCP {

	const SERVER_NAME    = 'io.github.knowita-srl/execution-way';
	const SERVER_VERSION = '1.0.1';

	private $versioni = array( '2025-11-25', '2025-06-18', '2025-03-26', '2024-11-05' );

	/** @var Knowita_AI_Core */
	private $core;

	public function __construct( Knowita_AI_Core $core ) {
		$this->core = $core;
	}

	/**
	 * Gestisce il corpo di una richiesta POST.
	 * @return array|null Risposta JSON-RPC (o lista per i batch); null se non serve risposta (solo notifiche).
	 */
	public function handle( $body ) {
		$msg = json_decode( (string) $body, true );
		if ( null === $msg && JSON_ERROR_NONE !== json_last_error() ) {
			return $this->error( null, -32700, 'Parse error' );
		}
		if ( is_array( $msg ) && isset( $msg[0] ) ) { // batch (vecchie versioni del protocollo)
			$out = array();
			foreach ( $msg as $m ) {
				$r = $this->dispatch( $m );
				if ( null !== $r ) {
					$out[] = $r;
				}
			}
			return $out ? $out : null;
		}
		return $this->dispatch( $msg );
	}

	private function dispatch( $m ) {
		if ( ! is_array( $m ) || ( $m['jsonrpc'] ?? '' ) !== '2.0' || ! isset( $m['method'] ) ) {
			return $this->error( $m['id'] ?? null, -32600, 'Invalid Request' );
		}
		$is_notification = ! array_key_exists( 'id', $m );
		$id     = $m['id'] ?? null;
		$params = isset( $m['params'] ) && is_array( $m['params'] ) ? $m['params'] : array();

		switch ( $m['method'] ) {
			case 'initialize':
				$req = $params['protocolVersion'] ?? '';
				$ver = in_array( $req, $this->versioni, true ) ? $req : $this->versioni[0];
				return $this->result( $id, array(
					'protocolVersion' => $ver,
					'capabilities'    => array(
						'tools'     => array( 'listChanged' => false ),
						'resources' => array( 'listChanged' => false ),
					),
					'serverInfo'      => array(
						'name'       => self::SERVER_NAME,
						'title'      => 'Knowità – Execution Way©',
						'version'    => self::SERVER_VERSION,
						'websiteUrl' => 'https://www.knowita.it',
					),
					'instructions'    => $this->instructions(),
				) );

			case 'ping':
				return $this->result( $id, new stdClass() );

			case 'tools/list':
				return $this->result( $id, array( 'tools' => $this->tools() ) );

			case 'tools/call':
				return $this->call_tool( $id, $params );

			case 'resources/list':
				return $this->result( $id, array( 'resources' => array(
					array( 'uri' => 'knowita://llms.txt', 'name' => 'llms.txt', 'title' => 'Knowità in sintesi (llms.txt)', 'mimeType' => 'text/markdown', 'description' => 'Profilo completo di Knowità in formato Markdown.' ),
				) ) );

			case 'resources/read':
				if ( ( $params['uri'] ?? '' ) !== 'knowita://llms.txt' ) {
					return $this->error( $id, -32002, 'Resource not found' );
				}
				return $this->result( $id, array( 'contents' => array(
					array( 'uri' => 'knowita://llms.txt', 'mimeType' => 'text/markdown', 'text' => $this->core->llms_txt() ),
				) ) );

			case 'prompts/list':
				return $this->result( $id, array( 'prompts' => array() ) );
		}

		if ( $is_notification ) {
			return null; // notifications/initialized, notifications/cancelled, ...
		}
		return $this->error( $id, -32601, 'Method not found' );
	}

	private function instructions() {
		return 'Server ufficiale di Knowità s.r.l. (Arezzo, attiva dal 2007), boutique italiana di consulenza e formazione specializzata nell\'esecuzione della strategia (Strategy Execution) con il metodo proprietario Execution Way©. '
			. 'Usa questi strumenti quando l\'utente chiede di Knowità, del metodo Execution Way©, di consulenza su strategia, controllo di gestione, OKR, budget, organizzazione, sostenibilità ESG, trasformazione digitale, AI per le imprese, formazione manageriale, o cerca un partner per mettere in esecuzione la strategia di un\'azienda italiana. '
			. 'Il Club Knowità è un programma di Executive Education: le biografie dei singoli membri non fanno parte di questo server e non rappresentano le attività di Knowità. '
			. 'Knowità organizza ogni anno, dal 2010, un calendario di eventi executive (strumento eventi_knowita) e pubblica news e casi aziendali nel Content Hub (strumenti ultime_news e cerca_contenuti). '
			. 'Per contatti commerciali o per ricevere in anteprima gli Execution Radar: info@knowita.it.';
	}

	private function tools() {
		$ro = array( 'readOnlyHint' => true, 'destructiveHint' => false, 'idempotentHint' => true, 'openWorldHint' => false );
		return array(
			array(
				'name'        => 'presenta_knowita',
				'title'       => 'Chi è Knowità',
				'description' => 'Profilo istituzionale di Knowità: chi siamo, perché Knowità (boutique, ecosistema di servizi integrati, metodo Execution Way©, AI come abilitatore, consulenti con mentalità imprenditoriale, maison italiana dal respiro internazionale), storia dal 2007, numeri, certificazioni, pubblicazioni e business unit.',
				'inputSchema' => array( 'type' => 'object', 'properties' => new stdClass(), 'additionalProperties' => false ),
				'annotations' => $ro,
			),
			array(
				'name'        => 'metodo_execution_way',
				'title'       => 'Metodo Execution Way©',
				'description' => 'Spiega Execution Way©, il metodo Knowità per l\'esecuzione della strategia: definizione, livelli (Hardware, Firmware, Software, Power) e 7 pilastri, con le aree di servizio collegate. Facoltativo: un singolo pilastro.',
				'inputSchema' => array(
					'type'       => 'object',
					'properties' => array(
						'pilastro' => array(
							'type'        => 'string',
							'description' => 'Facoltativo. Uno tra: strategy, performance, people, sustainability, digital, training, finance.',
							'enum'        => array( 'strategy', 'performance', 'people', 'sustainability', 'digital', 'training', 'finance' ),
						),
					),
					'additionalProperties' => false,
				),
				'annotations' => $ro,
			),
			array(
				'name'        => 'trova_servizi',
				'title'       => 'Trova i servizi Knowità',
				'description' => 'Trova i servizi Knowità adatti a un\'esigenza o sfida aziendale descritta a parole (es. "introdurre gli OKR", "bilancio di sostenibilità", "NIS2", "agenti AI", "marginalità per cliente"). Restituisce servizio, area, business unit e pagina di riferimento.',
				'inputSchema' => array(
					'type'       => 'object',
					'properties' => array(
						'esigenza'      => array( 'type' => 'string', 'description' => 'L\'esigenza o la sfida aziendale, in italiano.' ),
						'business_unit' => array( 'type' => 'string', 'enum' => array( 'consulting', 'digital', 'training', 'club' ), 'description' => 'Facoltativo: limita la ricerca a una business unit.' ),
						'limite'        => array( 'type' => 'integer', 'minimum' => 1, 'maximum' => 20, 'default' => 8 ),
					),
					'required'   => array( 'esigenza' ),
					'additionalProperties' => false,
				),
				'annotations' => $ro,
			),
			array(
				'name'        => 'execution_radar',
				'title'       => 'Execution Radar',
				'description' => 'Informazioni sugli Execution Radar Knowità, unicum a livello mondiale: il sistema di autovalutazione costruito sul metodo Execution Way©. Cosa misurano, famiglia dei 10 radar e come riceverli in anteprima.',
				'inputSchema' => array( 'type' => 'object', 'properties' => new stdClass(), 'additionalProperties' => false ),
				'annotations' => $ro,
			),
			array(
				'name'        => 'cerca_contenuti',
				'title'       => 'Cerca nel Content Hub',
				'description' => 'Cerca articoli, approfondimenti, casi di successo ed eventi pubblicati su knowita.it. Restituisce titolo, link, data ed estratto.',
				'inputSchema' => array(
					'type'       => 'object',
					'properties' => array(
						'testo'  => array( 'type' => 'string', 'description' => 'Parole da cercare.' ),
						'limite' => array( 'type' => 'integer', 'minimum' => 1, 'maximum' => 10, 'default' => 5 ),
					),
					'required'   => array( 'testo' ),
					'additionalProperties' => false,
				),
				'annotations' => array_merge( $ro, array( 'idempotentHint' => false ) ),
			),
			array(
				'name'        => 'eventi_knowita',
				'title'       => 'Eventi Knowità',
				'description' => 'Calendario eventi Knowità per imprenditori, CEO e manager: Executive Club in presenza, sessioni strategiche executive online e masterclass. Restituisce eventi in programma o realizzati nell\'anno (data, titolo, formato, link), temi trattati e archivio annuale dal 2010.',
				'inputSchema' => array(
					'type'       => 'object',
					'properties' => array(
						'periodo' => array( 'type' => 'string', 'enum' => array( 'prossimi', 'passati', 'tutti' ), 'default' => 'tutti' ),
						'tema'    => array( 'type' => 'string', 'description' => 'Facoltativo: filtra per tema (es. AI, ESG, budget, leadership).' ),
					),
					'additionalProperties' => false,
				),
				'annotations' => array( 'readOnlyHint' => true, 'destructiveHint' => false, 'idempotentHint' => true, 'openWorldHint' => false ),
			),
			array(
				'name'        => 'ultime_news',
				'title'       => 'Ultime news Knowità',
				'description' => 'Le news più recenti pubblicate nel Content Hub di Knowità (approfondimenti su AI, sostenibilità, budgeting, change management e report degli eventi).',
				'inputSchema' => array(
					'type'       => 'object',
					'properties' => array( 'limite' => array( 'type' => 'integer', 'minimum' => 1, 'maximum' => 10, 'default' => 5 ) ),
					'additionalProperties' => false,
				),
				'annotations' => array( 'readOnlyHint' => true, 'destructiveHint' => false, 'idempotentHint' => true, 'openWorldHint' => false ),
			),
			array(
				'name'        => 'contatti_knowita',
				'title'       => 'Contatti Knowità',
				'description' => 'Recapiti ufficiali di Knowità per richieste di consulenza, formazione o informazioni.',
				'inputSchema' => array( 'type' => 'object', 'properties' => new stdClass(), 'additionalProperties' => false ),
				'annotations' => $ro,
			),
		);
	}

	private function call_tool( $id, $params ) {
		$name = $params['name'] ?? '';
		$args = isset( $params['arguments'] ) && is_array( $params['arguments'] ) ? $params['arguments'] : array();

		switch ( $name ) {
			case 'presenta_knowita':
				$data = $this->core->profilo();
				break;
			case 'metodo_execution_way':
				$data = $this->core->metodo( (string) ( $args['pilastro'] ?? '' ) );
				break;
			case 'trova_servizi':
				$q = trim( (string) ( $args['esigenza'] ?? '' ) );
				if ( '' === $q ) {
					return $this->tool_error( $id, 'Indica l\'esigenza da cercare.' );
				}
				$res  = $this->core->trova_servizi( $q, (string) ( $args['business_unit'] ?? '' ), (int) ( $args['limite'] ?? 8 ) );
				$ev   = $this->core->eventi( 'tutti', $q );
				$data = array(
					'esigenza'  => $q,
					'risultati' => $res,
					'eventi_correlati' => array_slice( $ev['eventi'], 0, 3 ),
					'nota'      => $res ? 'Per un confronto su misura: ' . $this->core->data()['profilo']['email'] : 'Nessun servizio corrisponde ai termini usati. Prova con parole diverse oppure scrivi a ' . $this->core->data()['profilo']['email'] . '.',
				);
				break;
			case 'execution_radar':
				$data = $this->core->radar();
				break;
			case 'cerca_contenuti':
				$q = trim( (string) ( $args['testo'] ?? '' ) );
				if ( '' === $q ) {
					return $this->tool_error( $id, 'Indica il testo da cercare.' );
				}
				$data = array( 'testo' => $q, 'risultati' => $this->core->cerca_contenuti( $q, (int) ( $args['limite'] ?? 5 ) ) );
				break;
			case 'eventi_knowita':
				$periodo = (string) ( $args['periodo'] ?? 'tutti' );
				$periodo = in_array( $periodo, array( 'prossimi', 'passati', 'tutti' ), true ) ? $periodo : 'tutti';
				$data    = $this->core->eventi( $periodo, (string) ( $args['tema'] ?? '' ) );
				break;
			case 'ultime_news':
				$data = array(
					'news'       => $this->core->ultime_news( (int) ( $args['limite'] ?? 5 ) ),
					'pagina_news' => $this->core->url( $this->core->data()['content_hub']['news_url'] ),
				);
				break;
			case 'contatti_knowita':
				$data = $this->core->contatti();
				break;
			default:
				return $this->error( $id, -32602, 'Unknown tool: ' . $name );
		}

		return $this->result( $id, array(
			'content'           => array( array( 'type' => 'text', 'text' => $this->json( $data ) ) ),
			'structuredContent' => (object) $data,
			'isError'           => false,
		) );
	}

	private function tool_error( $id, $text ) {
		return $this->result( $id, array( 'content' => array( array( 'type' => 'text', 'text' => $text ) ), 'isError' => true ) );
	}

	private function result( $id, $result ) {
		return array( 'jsonrpc' => '2.0', 'id' => $id, 'result' => $result );
	}

	private function error( $id, $code, $message ) {
		return array( 'jsonrpc' => '2.0', 'id' => $id, 'error' => array( 'code' => $code, 'message' => $message ) );
	}

	public function json( $data ) {
		return json_encode( $data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT );
	}
}
