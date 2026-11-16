<?php
/**
 * Plugin Name:       Knowità AI – llms.txt, API e server MCP
 * Description:       Rende knowita.it leggibile da assistenti e agenti AI: llms.txt, dati strutturati schema.org, API REST pubblica (/wp-json/knowita-ai/v1/) e server MCP (/mcp). Indipendente dal tema: regge il restyling.
 * Version:           1.0.0
 * Requires PHP:      7.4
 * Author:            Knowità s.r.l.
 * Author URI:        https://www.knowita.it
 * License:           Proprietaria – riproduzione riservata
 * Text Domain:       knowita-ai
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'KNOWITA_AI_VERSION', '1.0.0' );
define( 'KNOWITA_AI_DIR', __DIR__ );

// Impostazioni (sovrascrivibili in wp-config.php).
if ( ! defined( 'KNOWITA_AI_SCHEMA' ) ) {
	define( 'KNOWITA_AI_SCHEMA', true );   // false se il plugin SEO genera già un'Organization completa.
}
if ( ! defined( 'KNOWITA_AI_LLMS' ) ) {
	define( 'KNOWITA_AI_LLMS', true );     // false se si preferisce gestire llms.txt con il plugin SEO.
}
if ( ! defined( 'KNOWITA_AI_REGISTRY_AUTH' ) ) {
	define( 'KNOWITA_AI_REGISTRY_AUTH', '' ); // es. 'v=MCPv1; k=ed25519; p=XXXX' per la verifica HTTP del registro MCP.
}

require_once KNOWITA_AI_DIR . '/includes/class-core.php';
require_once KNOWITA_AI_DIR . '/includes/class-mcp.php';

function knowita_ai_core() {
	static $core = null;
	if ( null === $core ) {
		$core = new Knowita_AI_Core( require KNOWITA_AI_DIR . '/includes/data.php' );
	}
	return $core;
}

function knowita_ai_mcp_url() {
	return home_url( '/mcp' );
}

function knowita_ai_api_url() {
	return rest_url( 'knowita-ai/v1/info' );
}

// ----------------------------------------------------------------------
// Percorsi "speciali": /llms.txt, /mcp, /.well-known/mcp-registry-auth
// ----------------------------------------------------------------------
add_action( 'init', 'knowita_ai_route', 0 );

function knowita_ai_route() {
	$path = wp_parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH );
	$home = wp_parse_url( home_url( '/' ), PHP_URL_PATH );
	if ( $home && '/' !== $home && 0 === strpos( $path, rtrim( $home, '/' ) ) ) {
		$path = substr( $path, strlen( rtrim( $home, '/' ) ) );
	}
	$path = '/' . trim( (string) $path, '/' );

	if ( '/llms.txt' === $path && KNOWITA_AI_LLMS ) {
		status_header( 200 );
		header( 'Content-Type: text/markdown; charset=utf-8' );
		header( 'X-Robots-Tag: noindex' );
		header( 'Cache-Control: public, max-age=3600' );
		$core = knowita_ai_core();
		$ev   = $core->eventi( 'prossimi' );
		echo $core->llms_txt( knowita_ai_mcp_url(), knowita_ai_api_url(), $ev['eventi'], $core->ultime_news( 5 ) ); // phpcs:ignore
		exit;
	}

	if ( '/.well-known/mcp-registry-auth' === $path && '' !== KNOWITA_AI_REGISTRY_AUTH ) {
		status_header( 200 );
		header( 'Content-Type: text/plain; charset=utf-8' );
		echo KNOWITA_AI_REGISTRY_AUTH; // phpcs:ignore
		exit;
	}

	if ( '/mcp' === $path ) {
		knowita_ai_serve_mcp();
	}
}

function knowita_ai_serve_mcp() {
	header( 'Access-Control-Allow-Origin: *' );
	header( 'Access-Control-Allow-Methods: POST, GET, OPTIONS' );
	header( 'Access-Control-Allow-Headers: Content-Type, Accept, Authorization, Mcp-Session-Id, MCP-Protocol-Version, Last-Event-ID' );
	header( 'Access-Control-Expose-Headers: Mcp-Session-Id, MCP-Protocol-Version' );
	header( 'Cache-Control: no-store' );
	header( 'X-Robots-Tag: noindex' );

	$method = strtoupper( $_SERVER['REQUEST_METHOD'] ?? 'GET' );

	if ( 'OPTIONS' === $method ) {
		status_header( 204 );
		exit;
	}

	if ( 'POST' !== $method ) {
		// Il server non apre flussi SSE: per lo standard MCP si risponde 405.
		$accept = $_SERVER['HTTP_ACCEPT'] ?? '';
		status_header( 405 );
		header( 'Allow: POST, OPTIONS' );
		if ( false !== strpos( $accept, 'text/html' ) ) {
			header( 'Content-Type: text/html; charset=utf-8' );
			echo '<!doctype html><meta charset="utf-8"><title>Knowità – server MCP</title>'
				. '<p>Questo è il server MCP (Model Context Protocol) di Knowità, in sola lettura.</p>'
				. '<p>Indirizzo da configurare nel vostro assistente AI: <code>' . esc_html( knowita_ai_mcp_url() ) . '</code> (trasporto Streamable HTTP).</p>'
				. '<p><a href="' . esc_url( home_url( '/' ) ) . '">Torna a knowita.it</a></p>';
		}
		exit;
	}

	$body = file_get_contents( 'php://input' );
	if ( strlen( $body ) > 100000 ) {
		status_header( 413 );
		exit;
	}

	$server   = new Knowita_AI_MCP( knowita_ai_core() );
	$response = $server->handle( $body );

	if ( null === $response ) {
		status_header( 202 );
		exit;
	}

	status_header( 200 );
	header( 'Content-Type: application/json; charset=utf-8' );
	echo wp_json_encode( $response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
	exit;
}

// ----------------------------------------------------------------------
// API REST pubblica: /wp-json/knowita-ai/v1/
// ----------------------------------------------------------------------
add_action( 'rest_api_init', 'knowita_ai_rest' );

function knowita_ai_rest() {
	$ns   = 'knowita-ai/v1';
	$open = '__return_true';
	$core = knowita_ai_core();

	$routes = array(
		'/info'     => function () {
			return array(
				'nome'        => 'API Knowità',
				'descrizione' => 'API pubblica in sola lettura su Knowità, il metodo Execution Way© e i servizi. Dati in italiano.',
				'versione'    => KNOWITA_AI_VERSION,
				'aggiornamento_contenuti' => knowita_ai_core()->data()['versione_contenuti'],
				'server_mcp'  => knowita_ai_mcp_url(),
				'llms_txt'    => home_url( '/llms.txt' ),
				'endpoint'    => array(
					'profilo'   => rest_url( 'knowita-ai/v1/profilo' ),
					'metodo'    => rest_url( 'knowita-ai/v1/metodo' ) . '?pilastro=strategy',
					'servizi'   => rest_url( 'knowita-ai/v1/servizi' ) . '?q=okr&business_unit=consulting',
					'radar'     => rest_url( 'knowita-ai/v1/radar' ),
					'contenuti' => rest_url( 'knowita-ai/v1/contenuti' ) . '?q=budget',
					'eventi'    => rest_url( 'knowita-ai/v1/eventi' ) . '?periodo=prossimi',
					'news'      => rest_url( 'knowita-ai/v1/news' ),
					'contatti'  => rest_url( 'knowita-ai/v1/contatti' ),
					'schema'    => rest_url( 'knowita-ai/v1/schema' ),
				),
			);
		},
		'/profilo'  => function () use ( $core ) {
			return $core->profilo();
		},
		'/metodo'   => function ( WP_REST_Request $r ) use ( $core ) {
			return $core->metodo( sanitize_text_field( (string) $r->get_param( 'pilastro' ) ) );
		},
		'/servizi'  => function ( WP_REST_Request $r ) use ( $core ) {
			$q = sanitize_text_field( (string) $r->get_param( 'q' ) );
			$b = sanitize_text_field( (string) $r->get_param( 'business_unit' ) );
			$l = (int) ( $r->get_param( 'limite' ) ?: 8 );
			return '' === $q && '' === $b ? $core->servizi() : $core->trova_servizi( $q, $b, $l );
		},
		'/radar'    => function () use ( $core ) {
			return $core->radar();
		},
		'/contenuti' => function ( WP_REST_Request $r ) use ( $core ) {
			$q = sanitize_text_field( (string) $r->get_param( 'q' ) );
			if ( '' === $q ) {
				return new WP_Error( 'knowita_q', 'Parametro q obbligatorio.', array( 'status' => 400 ) );
			}
			return $core->cerca_contenuti( $q, (int) ( $r->get_param( 'limite' ) ?: 5 ) );
		},
		'/eventi'   => function ( WP_REST_Request $r ) use ( $core ) {
			$p = sanitize_text_field( (string) ( $r->get_param( 'periodo' ) ?: 'tutti' ) );
			return $core->eventi( in_array( $p, array( 'prossimi', 'passati', 'tutti' ), true ) ? $p : 'tutti', sanitize_text_field( (string) $r->get_param( 'tema' ) ) );
		},
		'/news'     => function ( WP_REST_Request $r ) use ( $core ) {
			return $core->ultime_news( (int) ( $r->get_param( 'limite' ) ?: 5 ) );
		},
		'/contatti' => function () use ( $core ) {
			return $core->contatti();
		},
		'/schema'   => function () use ( $core ) {
			return $core->schema_org();
		},
	);

	foreach ( $routes as $route => $cb ) {
		register_rest_route( $ns, $route, array(
			'methods'             => 'GET',
			'callback'            => $cb,
			'permission_callback' => $open,
		) );
	}
}

// CORS aperto solo per le rotte knowita-ai/v1 (dati pubblici, sola lettura).
add_filter( 'rest_post_dispatch', function ( $response, $server, $request ) {
	if ( 0 === strpos( $request->get_route(), '/knowita-ai/v1' ) && $response instanceof WP_REST_Response ) {
		$response->header( 'Access-Control-Allow-Origin', '*' );
		$response->header( 'Cache-Control', 'public, max-age=3600' );
	}
	return $response;
}, 10, 3 );

// ----------------------------------------------------------------------
// schema.org JSON-LD su home e Chi siamo
// ----------------------------------------------------------------------
add_action( 'wp_head', function () {
	if ( ! KNOWITA_AI_SCHEMA ) {
		return;
	}
	if ( ! ( is_front_page() || is_page( 'about' ) ) ) {
		return;
	}
	echo "\n<script type=\"application/ld+json\">" . wp_json_encode( knowita_ai_core()->schema_org(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . "</script>\n"; // phpcs:ignore
}, 20 );

// Segnala llms.txt ai crawler che lo cercano nell'head.
add_action( 'wp_head', function () {
	if ( KNOWITA_AI_LLMS && is_front_page() ) {
		echo '<link rel="alternate" type="text/markdown" title="llms.txt" href="' . esc_url( home_url( '/llms.txt' ) ) . '">' . "\n";
	}
}, 21 );
