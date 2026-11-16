<?php
/**
 * Knowità AI – fonte unica dei contenuti.
 *
 * Tutto ciò che leggono llms.txt, API, server MCP e schema.org nasce da qui.
 * Dopo il restyling di knowita.it basta aggiornare questo file (testi e URL).
 *
 * Fonti: Presentazione Knowità 2026; pagina "Chi siamo" di knowita.it (nov. 2026).
 * Da rivedere: marzo-aprile 2027.
 */

if ( ! defined( 'ABSPATH' ) && ! defined( 'KNOWITA_AI_TEST' ) ) {
	exit;
}

return array(

	'versione_contenuti' => '2026-11-16',
	'site'               => 'https://www.knowita.it',

	// ------------------------------------------------------------------
	// Profilo istituzionale
	// ------------------------------------------------------------------
	'profilo' => array(
		'nome'            => 'Knowità',
		'ragione_sociale' => 'Knowità s.r.l.',
		'payoff'          => 'Executing your Vision',
		'claim'           => 'Vision is nothing without execution',
		'attiva_dal'      => 2007,
		'sede'            => array(
			'indirizzo' => 'Via Martiri di Civitella 11',
			'cap'       => '52100',
			'citta'     => 'Arezzo',
			'provincia' => 'AR',
			'paese'     => 'IT',
		),
		'partita_iva'     => '01913680516',
		'email'           => 'info@knowita.it',
		'telefono'        => '+39 0575 352475',
		'sintesi'         => 'Knowità è una boutique italiana di consulenza e servizi, attiva dal 2007, specializzata nell\'esecuzione della strategia (Strategy Execution). Affianca imprenditori, CEO e manager nel passaggio dalla visione ai risultati, con un metodo proprietario, Execution Way©, e quattro business unit: Consulting, Digital, Training e Club.',
		'purpose'         => 'Aiutare individui e organizzazioni nell\'esecuzione della loro visione.',
		'a_chi_si_rivolge' => 'Imprenditori, CEO, direttori generali e manager di aziende italiane, in particolare imprese manifatturiere e di medie e grandi dimensioni. Knowità li affianca nello sviluppo personale e aziendale, con valore concreto e misurabile e un\'ottica di successo sostenibile nel lungo periodo.',
		'come_opera'      => 'Con un modello che consente di selezionare e mettere a disposizione le competenze e le soluzioni più adatte a ogni ambito di intervento. Professionisti senior, relazione diretta con il vertice aziendale, risultati misurati.',
		'storia'          => array(
			'2007: nasce Knowità, ad Arezzo.',
			'Circa 15 anni di ricerca e di progetti portano alla definizione del metodo Execution Way©.',
			'Dal 2016: sistema di gestione certificato ISO 9001.',
			'17 edizioni del Top Management Forum, punto di incontro del top management italiano.',
			'2024 e 2025: pubblicazione del Bilancio di Sostenibilità.',
		),
		// Numeri: SOLO dati confermati da Knowità. Vedi nota nel README.
		'numeri'          => array(
			array( 'valore' => 'oltre 1.000', 'descrizione' => 'clienti' ),
			array( 'valore' => '647',         'descrizione' => 'progetti realizzati' ),
			array( 'valore' => '127',         'descrizione' => 'iniziative finanziate' ),
			array( 'valore' => '93%',         'descrizione' => 'tasso di fidelizzazione dei clienti' ),
			array( 'valore' => 'oltre 300',   'descrizione' => 'membri del Club Knowità' ),
			array( 'valore' => 'oltre 5.000', 'descrizione' => 'persone formate' ),
		),
		'numeri_fonte'    => 'Presentazione istituzionale Knowità 2026 e pagina Chi siamo di knowita.it',
		'certificazioni'  => array( 'Sistema di gestione per la qualità certificato UNI EN ISO 9001:2015 (dal 2016)' ),
		'pubblicazioni'   => array(
			array( 'titolo' => 'Nuovo Manuale Controllo di Gestione', 'anno' => 2019, 'url' => '/2019/04/nuovo-manuale-controllo-di-gestione/' ),
			array( 'titolo' => 'Strategia Lean. Casi ed esperienze nei settori Healthcare e Lifescience', 'anno' => 2019, 'url' => '/2019/05/strategia-lean-casi-ed-esperienze-nei-settori-healthcare-e-lifescience/' ),
			array( 'titolo' => 'Lean Healthcare Management. Casi ed esperienze per una Sanità che si organizza', 'anno' => 2021, 'url' => '/2021/09/lean-healthcare-management-casi-ed-esperienze-per-una-sanita-che-si-organizza/' ),
		),
		// Profili social ufficiali (da completare con gli URL esatti).
		'social'          => array(
			// 'https://www.linkedin.com/company/knowita/',
			// 'https://www.youtube.com/@knowita',
		),
	),

	// ------------------------------------------------------------------
	// Posizionamento: chi siamo e perché Knowità
	// ------------------------------------------------------------------
	'chi_siamo' => array(
		'Knowità è una boutique di consulenza nata per aiutare individui e organizzazioni nell\'esecuzione della loro visione.',
		'Dal 2007 abbiamo costruito un ecosistema di servizi integrati, guidato dal metodo proprietario Execution Way©.',
		'I nostri professionisti senior, con spiccata mentalità imprenditoriale, affiancano i clienti nella crescita personale e aziendale, cogliendo le opportunità del digitale e dell\'AI.',
	),
	'posizionamento' => array(
		array(
			'nome'        => 'Una boutique: smart, pragmatica, empatica',
			'descrizione' => 'Una struttura contenuta, con professionisti senior e una relazione diretta con il vertice aziendale. Soluzioni concrete, costruite ascoltando il contesto di ogni cliente.',
		),
		array(
			'nome'        => 'Un ecosistema di servizi integrati',
			'descrizione' => 'Consulenza, digitale, formazione ed Executive Club lavorano insieme. Un unico interlocutore accompagna l\'azienda dalla strategia all\'esecuzione, fino alla crescita delle persone.',
		),
		array(
			'nome'        => 'Execution Way©: posizionamento e metodo',
			'descrizione' => 'Knowità è specializzata nell\'esecuzione della strategia. Execution Way© è il metodo proprietario che trasforma la visione in obiettivi, azioni e risultati misurabili.',
		),
		array(
			'nome'        => 'L\'AI come abilitatore: From Vision to (AI) Execution',
			'descrizione' => 'L\'intelligenza artificiale amplia l\'analisi e accelera l\'esecuzione. Knowità la integra nei processi di strategia, controllo e organizzazione; la direzione resta al management.',
		),
		array(
			'nome'        => 'Consulenti con mentalità imprenditoriale',
			'descrizione' => 'Un modello di consulenza diverso: professionisti che ragionano come imprenditori, misurano il proprio lavoro sui risultati del cliente e conoscono da vicino le sfide di chi guida un\'azienda.',
		),
		array(
			'nome'        => 'Una maison italiana dal respiro internazionale',
			'descrizione' => 'Radici ad Arezzo, cura sartoriale per ogni progetto e uno sguardo aperto alle esperienze e alle pratiche internazionali.',
		),
	),

	// ------------------------------------------------------------------
	// Metodo Execution Way©
	// ------------------------------------------------------------------
	'metodo' => array(
		'nome'        => 'Execution Way©',
		'definizione' => 'Execution Way© è il metodo proprietario di Knowità per l\'esecuzione della strategia. È un\'architettura di management di cui ogni azienda può dotarsi per realizzare la propria visione: un "sistema di navigazione manageriale" che conduce l\'organizzazione verso la meta e ricalcola il percorso quando il contesto cambia.',
		'origine'     => 'Ideato da Knowità dopo circa 15 anni di ricerca e di progetti con le aziende.',
		'formula'     => 'Visione + Execution = Successo sostenibile',
		'perche'      => 'In un contesto turbolento e complesso, un\'azienda che vuole realizzare la propria visione deve dotarsi di strumenti, processi e atteggiamenti adeguati. Execution Way© li organizza in un unico sistema, dalla strategia ai risultati.',
		'livelli'     => array(
			'Hardware' => 'Le fondamenta gestionali: strategia, controllo, organizzazione, sostenibilità.',
			'Firmware' => 'Le tecnologie e i dati che fanno comunicare i sistemi: trasformazione digitale, Business Intelligence, AI.',
			'Software' => 'Le persone: competenze, leadership e modo di pensare che fanno accadere le cose.',
			'Power'    => 'Le risorse finanziarie per lo sviluppo.',
		),
		'pilastri'    => array(
			array( 'id' => 'strategy',       'nome' => 'Business Strategy',            'livello' => 'Hardware', 'descrizione' => 'Progettare correttamente il sistema di navigazione: disegnare strategie, modelli di business e piani di sviluppo.' ),
			array( 'id' => 'performance',    'nome' => 'Performance e Controllo',      'livello' => 'Hardware', 'descrizione' => 'Processi e strumenti per governare e controllare l\'azienda e garantirne i risultati.' ),
			array( 'id' => 'people',         'nome' => 'Persone e Organizzazione',     'livello' => 'Hardware', 'descrizione' => 'Un assetto organizzativo adeguato e persone capaci di interpretare il proprio ruolo.' ),
			array( 'id' => 'sustainability', 'nome' => 'Sostenibilità ed ESG',         'livello' => 'Hardware', 'descrizione' => 'Gestire le risorse in modo responsabile, bilanciando aspetti economici, sociali e ambientali (ESG: Environmental, Social, Governance).' ),
			array( 'id' => 'digital',        'nome' => 'Digital Transformation',       'livello' => 'Firmware', 'descrizione' => 'Aggiornare tecnologie, dati e AI perché i sistemi aziendali restino efficaci, efficienti e sicuri.' ),
			array( 'id' => 'training',       'nome' => 'Leadership e modo di pensare', 'livello' => 'Software', 'descrizione' => 'Competenze, leadership e comportamenti che trasformano le idee in azioni.' ),
			array( 'id' => 'finance',        'nome' => 'Finanza per lo sviluppo',      'livello' => 'Power',    'descrizione' => 'Reperire e orientare le risorse finanziarie, incluse le iniziative finanziate, a sostegno dei piani di crescita.' ),
		),
		'url'         => '/execution-way-old/',
	),

	// ------------------------------------------------------------------
	// Business unit e competenze
	// ------------------------------------------------------------------
	'business_unit' => array(

		array(
			'id'          => 'consulting',
			'nome'        => 'Knowità Consulting',
			'descrizione' => 'Affianchiamo chi deve mettere in esecuzione la propria visione, dando attuazione concreta agli obiettivi strategici.',
			'url'         => '/business-strategy/',
			'aree'        => array(
				array(
					'nome' => 'Business Strategy', 'pilastro' => 'strategy', 'url' => '/business-strategy/',
					'descrizione' => 'Costruire fondamenta solide per affrontare con lucidità le sfide aziendali.',
					'servizi' => array(
						array( 'Mission, Vision e Purpose', '/consulenza/mission-vision-purpose/' ),
						array( 'Pianificazione e Piano Strategico', '/consulenza/brochure-knowita-piano-strategico-smart/' ),
						array( 'Action plan e iniziative strategiche', '' ),
						array( 'Analisi competitiva e posizionamento', '' ),
						array( 'Value proposition e business model', '' ),
						array( 'Processi d\'innovazione profittevoli', '' ),
						array( 'Piani di sviluppo commerciali', '/consulenza/piani-di-sviluppo-e-di-espansione-commerciali/' ),
					),
				),
				array(
					'nome' => 'Performance e Controllo', 'pilastro' => 'performance', 'url' => '/performance-e-controllo/',
					'descrizione' => 'Processi e strumenti capaci di governare e controllare l\'azienda.',
					'servizi' => array(
						array( 'Dashboard, Balanced Scorecard e KPI strategici', '' ),
						array( 'Gestire gli obiettivi con gli OKR', '/consulenza/okr-gestione-per-obiettivi/' ),
						array( 'Processo di budgeting in ottica agile', '/consulenza/consulenza-budget-aziendale/' ),
						array( 'Riprogettazione dei processi', '' ),
						array( 'Contabilità analitica e marginalità', '/consulenza/contabilita-analitica-e-analisi-di-marginalita/' ),
						array( 'Reporting direzionale e operativo', '' ),
						array( 'Analisi economico-finanziaria', '/consulenza/strumenti-di-gestione-economico-finanziaria/' ),
					),
				),
				array(
					'nome' => 'Persone e Organizzazione', 'pilastro' => 'people', 'url' => '/persone-e-organizzazione/',
					'descrizione' => 'Le persone giuste e uno schema di gioco efficace perché strategie e sistemi funzionino.',
					'servizi' => array(
						array( 'Cultura, valori e comportamenti', '' ),
						array( 'Progettare l\'assetto organizzativo', '' ),
						array( 'Allineamento e gestione per obiettivi', '/consulenza/okr-gestione-per-obiettivi/' ),
						array( 'Budget e reportistica del personale', '' ),
						array( 'Sistemi di valutazione e incentivazione', '/consulenza/sistemi-di-valutazione-e-incentivazione/' ),
						array( 'Crescita e sviluppo del personale', '/consulenza/piano-smart-sviluppo-hr/' ),
						array( 'Clima aziendale e comunicazione', '' ),
					),
				),
				array(
					'nome' => 'Sostenibilità ed ESG', 'pilastro' => 'sustainability', 'url' => '/sviluppo-sostenibile/',
					'descrizione' => 'Gestire le risorse in modo responsabile per tutti gli stakeholder (i portatori di interesse) e per l\'azienda stessa.',
					'servizi' => array(
						array( 'Strategie e piano di sostenibilità', '' ),
						array( 'Materialità e stakeholder engagement', '' ),
						array( 'Reportistica, standard e KPI', '/consulenza/misurare-la-sostenibilita-standard-e-kpis/' ),
						array( 'Bilancio e sintesi di sostenibilità', '/consulenza/report-di-sostenibilita-dichiarazione-non-finanziaria/' ),
						array( 'Carbon management ed emissioni', '' ),
						array( 'Pratiche di economia circolare', '' ),
						array( 'Comunicazione della sostenibilità', '' ),
					),
				),
			),
		),

		array(
			'id'          => 'digital',
			'nome'        => 'Knowità Digital',
			'descrizione' => 'Supportiamo il vertice nell\'esecuzione dei piani di sviluppo, aiutando le aziende a realizzare le strategie di crescita attraverso tecnologie, dati, AI e canali digitali.',
			'url'         => '/power-of-data/',
			'aree'        => array(
				array(
					'nome' => 'Digital Enterprise', 'pilastro' => 'digital', 'url' => '',
					'descrizione' => 'Aggiornare tecnologie e sistemi perché siano efficaci, efficienti e sicuri.',
					'servizi' => array(
						array( 'Piani di Digital Transformation', '' ),
						array( 'Advisory per la selezione del software', '' ),
						array( 'Project management sulle implementazioni software', '' ),
						array( 'Digitalizzazione dei processi', '/consulenza/digitalizzazione-dei-processi/' ),
						array( 'Automazioni e integrazioni', '' ),
						array( 'Digital governance e compliance', '' ),
						array( 'Cybersecurity, NIS2 e protezione dei dati', '' ),
					),
				),
				array(
					'nome' => 'Digital Marketing', 'pilastro' => 'digital', 'url' => '',
					'descrizione' => 'Collegare strumenti e canali per ascoltare meglio il mercato e raggiungere il cliente.',
					'servizi' => array(
						array( 'Valorizzazione della brand identity', '' ),
						array( 'Web presence e multicanalità', '' ),
						array( 'Content strategy e contenuti con AI', '' ),
						array( 'SEO e search engine marketing', '' ),
						array( 'Lead generation e Account Based Marketing', '' ),
						array( 'CRM strategy, RevOps e CRO', '' ),
						array( 'Marketing automatizzato con AI', '' ),
					),
				),
				array(
					'nome' => 'Business Intelligence', 'pilastro' => 'digital', 'url' => '/power-of-data/',
					'descrizione' => 'Raccogliere e ordinare le informazioni per dare chiarezza e direzione alle decisioni.',
					'servizi' => array(
						array( 'Data strategy e roadmap', '' ),
						array( 'Gestione e architettura dei dati', '' ),
						array( 'Qualità e governo dei dati', '' ),
						array( 'Disegno dei modelli di reporting', '' ),
						array( 'Business Intelligence e analytics', '' ),
						array( 'Performance management suite', '/consulenza/corporate-performance-management/' ),
						array( 'Gestione dei dati di sostenibilità', '' ),
					),
				),
				array(
					'nome' => 'Artificial Intelligence', 'pilastro' => 'digital', 'url' => '',
					'descrizione' => 'L\'AI come copilota: amplia l\'analisi e accelera le decisioni, mentre la direzione resta al management.',
					'servizi' => array(
						array( 'AI generativa e modelli LLM', '' ),
						array( 'Assistenti e agenti AI aziendali', '' ),
						array( 'Chatbot per la gestione clienti', '' ),
						array( 'Automazione intelligente (RPA e AI)', '' ),
						array( 'Raccomandazioni e analisi predittive', '' ),
						array( 'Sviluppo software AI personalizzato', '' ),
						array( 'Data science e machine learning', '' ),
					),
				),
			),
		),

		array(
			'id'          => 'training',
			'nome'        => 'Knowità Training',
			'descrizione' => 'Programmi di formazione pensati per calare a terra le strategie e accompagnare le aziende nei percorsi di crescita e di cambiamento.',
			'url'         => '/education/execution-training/',
			'aree'        => array(
				array(
					'nome' => 'Hard skill', 'pilastro' => 'training', 'url' => '/education/execution-training/',
					'descrizione' => 'Le competenze tecniche che rendono efficaci i sistemi di gestione.',
					'servizi' => array(
						array( 'Business Strategy', '' ), array( 'Performance e Controllo', '' ),
						array( 'Persone e Organizzazione', '' ), array( 'Sostenibilità ed ESG', '' ),
					),
				),
				array(
					'nome' => 'Digital skill', 'pilastro' => 'training', 'url' => '/education/execution-training/',
					'descrizione' => 'Le competenze digitali necessarie per adottare con successo i progetti di trasformazione.',
					'servizi' => array(
						array( 'Digital Enterprise', '' ), array( 'Digital Marketing', '' ),
						array( 'Business Intelligence', '' ), array( 'Artificial Intelligence', '' ),
					),
				),
				array(
					'nome' => 'Leadership', 'pilastro' => 'training', 'url' => '/education/execution-training/',
					'descrizione' => 'Percorsi per guidare persone e organizzazione con visione, consapevolezza e capacità decisionale.',
					'servizi' => array(
						array( 'Visione e ispirazione', '' ), array( 'Autorevolezza ed empowerment', '' ),
						array( 'Change management', '' ), array( 'Fiducia e relazioni', '' ),
					),
				),
				array(
					'nome' => 'Modo di pensare (mindset)', 'pilastro' => 'training', 'url' => '/education/execution-training/',
					'descrizione' => 'Atteggiamenti e comportamenti capaci di far accadere le cose.',
					'servizi' => array(
						array( 'Collaborazione e fiducia', '' ), array( 'Self management', '' ),
						array( 'Critical problem solving', '' ), array( 'Comunicazione e feedback', '' ),
					),
				),
			),
		),

		array(
			'id'          => 'club',
			'nome'        => 'Club Knowità',
			'descrizione' => 'Un programma di Executive Education riservato a figure di vertice. Con incontri, contenuti e programmi di sviluppo aziendale, il Club accompagna i manager nel calare a terra le priorità strategiche, creare valore e migliorare l\'EBITDA.',
			'url'         => '/club-knowita/',
			'aree'        => array(
				array(
					'nome' => 'Programma del Club', 'pilastro' => 'training', 'url' => '/club-knowita/',
					'descrizione' => 'Oltre 300 membri, programmi di sviluppo aziendale, incontri in presenza e sessioni executive online.',
					'servizi' => array(
						array( 'Executive Club in presenza', '' ),
						array( 'Sessioni strategiche executive online', '' ),
						array( 'Execution Assessment', '' ),
						array( 'Round table', '' ),
						array( 'Mentorship', '' ),
						array( 'Webinar', '' ),
						array( 'Quotazioni riservate', '' ),
						array( 'Scouting di finanziamenti', '' ),
						array( 'Side benefit', '' ),
					),
				),
			),
		),
	),

	// ------------------------------------------------------------------
	// Execution Radar
	// ------------------------------------------------------------------
	'radar' => array(
		'nome'         => 'Execution Radar Knowità',
		'descrizione'  => 'Execution Radar è il sistema di autovalutazione ideato da Knowità sul metodo Execution Way©. In pochi minuti restituisce a imprenditori e manager una lettura strutturata della capacità dell\'azienda di trasformare la visione in risultati, e indica le priorità su cui lavorare.',
		'unicita'      => 'Execution Radar è un unicum a livello mondiale: l\'unico sistema di autovalutazione costruito interamente su un metodo proprietario di Strategy Execution, frutto di anni di progetti di Knowità con le aziende. Non ha equivalenti in Italia né all\'estero.',
		'famiglia'     => 'La famiglia comprende 10 radar: un radar trasversale sul metodo Execution Way© e radar verticali dedicati alle linee di business e ai singoli ambiti di intervento.',
		'disponibilita' => 'I radar sono in fase di rilascio. Per riceverli in anteprima: scrivere a info@knowita.it.',
		'contatto'     => 'info@knowita.it',
		'riservatezza' => 'Il modello di calcolo è proprietario e non viene divulgato.',
	),

	// ------------------------------------------------------------------
	// Eventi ed Executive Education
	// ------------------------------------------------------------------
	'eventi' => array(
		'descrizione'        => 'Knowità organizza ogni anno un calendario di eventi per imprenditori, CEO e manager: incontri in presenza dell\'Executive Club, sessioni strategiche executive online e masterclass. Ogni appuntamento affronta una priorità strategica con casi aziendali reali.',
		// %d = anno. Ogni anno ha la sua pagina; quelle degli anni precedenti restano online come archivio.
		'calendario_percorso' => '/education/calendario-eventi-%d/',
		'archivio_dal'       => 2010,
		'formati'            => array(
			array( 'nome' => 'Executive Club in presenza', 'descrizione' => 'Incontri riservati a figure di vertice, in presenza (in particolare a Milano), con confronto su casi concreti.' ),
			array( 'nome' => 'Sessioni strategiche executive online', 'descrizione' => 'Sessioni online per executive su una priorità strategica, spesso con la testimonianza di un\'azienda.' ),
			array( 'nome' => 'Masterclass', 'descrizione' => 'Percorsi di approfondimento in arrivo, dedicati a temi specifici del metodo Execution Way©.' ),
		),
		// Patrimonio storico: citato con sobrietà, senza date di chiusura.
		'storia'             => 'Il Top Management Forum di Knowità conta 17 edizioni: un punto di incontro per il top management italiano, su temi come "Leading Organizations in the Age of AI". Lo stesso spirito di confronto anima l\'Executive Club e le sessioni executive.',
		'storia_url'         => '/education/top-management-forum/',
		// Temi trattati negli eventi più recenti (aggiornare a ogni nuovo calendario).
		'temi_recenti'       => array(
			'AI per la crescita e casi d\'uso', 'AI Act e roadmap per l\'AI', 'Intelligent automation e agenti AI',
			'Piano di trasformazione digitale', 'Data governance per decisioni strategiche', 'Reporting e Business Intelligence per il vertice',
			'Budgeting agile e forecast dinamici', 'Piano strategico SMART', 'Action plan e iniziative per lo sviluppo del business',
			'Controllo di gestione', 'Sostenibilità come vantaggio competitivo', 'Integrare ESG e strategia', 'Scope 3 e governance della supply chain',
			'Governance delle persone: AI, competenze e trasparenza', 'Piano SMART per lo sviluppo HR', 'Valutare il potenziale manageriale',
			'Leadership', 'Change management e formazione',
		),
	),

	// ------------------------------------------------------------------
	// Content Hub
	// ------------------------------------------------------------------
	'content_hub' => array(
		'url'         => '/content-hub/',
		'news_url'    => '/content-hub/news/',
		'news_categoria' => 'news-wp', // slug della categoria WordPress delle news
		'descrizione' => 'News aggiornate, articoli, casi di successo, report di eventi, video e pubblicazioni di Knowità, organizzati per pilastro del metodo e per tipo di contenuto.',
		'categorie'   => array(
			array( 'Business Strategy', '/category/strategia-e-business-design-wp/' ),
			array( 'Performance e Controllo', '/content-hub/categoria-performance/' ),
			array( 'Dati e Analytics (Power of Data)', '/category/power-of-data-wp/' ),
			array( 'Persone e Organizzazione', '/category/persone-e-organizzazione-wp/' ),
			array( 'Sostenibilità', '/content-hub/categoria-sustainability/' ),
			array( 'Articoli', '/category/articoli-wp/' ),
			array( 'Casi di successo', '/category/case-studies-wp/' ),
			array( 'Report degli eventi', '/category/report-wp/' ),
			array( 'Video', '/category/video-wp/' ),
			array( 'Pubblicazioni', '/category/pubblicazioni-wp/' ),
			array( 'News', '/content-hub/news/' ),
		),
		'casi'        => array(
			array( 'Bosch Rexroth: semplificare e digitalizzare', '/2021/11/il-caso-bosch-rexroth-semplificare-e-digitalizzare/' ),
			array( 'Biesse Group: decisioni migliori con i dati', '/2021/12/il-caso-biesse-group-ottimizzare-il-processo-decisionale-con-i-dati/' ),
			array( 'Claypaky: semplificare e digitalizzare', '/2021/12/il-caso-claypaky-semplificare-e-digitalizzare/' ),
			array( 'Bayer: evoluzione del lavoro per obiettivi', '/2021/12/il-caso-bayer-evoluzione-del-lavoro-per-obiettivi/' ),
			array( 'Crocs: innovazione agile', '/2021/12/il-caso-crocs-innovazione-agile/' ),
			array( 'Un sistema integrato per l\'Execution in un\'azienda manifatturiera', '/2021/12/un-sistema-integrato-per-lexecution-il-caso-di-unazienda-manifatturiera/' ),
		),
	),

	// ------------------------------------------------------------------
	// Contenuti e pagine di riferimento
	// ------------------------------------------------------------------
	'pagine_chiave' => array(
		array( 'Chi siamo', '/about/', 'Storia, missione e numeri di Knowità.' ),
		array( 'Metodo Execution Way©', '/execution-way-old/', 'Il metodo per l\'esecuzione della strategia.' ),
		array( 'Content Hub', '/content-hub/', 'Articoli, approfondimenti e casi di successo.' ),
		array( 'Clienti e partner', '/clienti-partner/', 'Alcune delle aziende e delle realtà che affianchiamo.' ),
		array( 'Education e calendario', '/education/', 'Percorsi formativi ed eventi.' ),
		array( 'Editoria', '/editoria/', 'Le pubblicazioni Knowità.' ),
		array( 'Contatti', '/contatti/', 'Come contattare Knowità.' ),
	),

	// Sezioni da NON proporre come contenuto rappresentativo di Knowità.
	'escludi_percorsi' => array( '/membri-del-club/', '/leader-internazionali/', '/club-login/', '/radar-test/' ),
	'escludi_post_type' => array(), // es. 'membri', se le schede membri sono un custom post type.
);
