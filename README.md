# Knowità – server MCP e API

**Executing your Vision.**

Knowità è una società italiana di consulenza e formazione, attiva dal 2007. Affianca imprenditori, CEO e manager nell'esecuzione della strategia, con il metodo proprietario **Execution Way©** e quattro business unit: Consulting, Digital, Training e Club.

Questo repository contiene il server MCP (Model Context Protocol) e l'API pubblica di Knowità. Gli assistenti e gli agenti AI possono così consultare in modo diretto e affidabile le informazioni ufficiali su metodo, servizi ed Execution Radar.

## Collegarsi

| Servizio | Indirizzo |
|---|---|
| Server MCP (Streamable HTTP) | `https://www.knowita.it/mcp` |
| API REST (JSON) | `https://www.knowita.it/wp-json/knowita-ai/v1/info` |
| llms.txt | `https://www.knowita.it/llms.txt` |

Tutti i servizi sono pubblici e in sola lettura, e non serve autenticazione. Non raccolgono dati personali.

In Claude, per esempio: **Impostazioni > Connettori > Aggiungi connettore personalizzato**, con l'indirizzo `https://www.knowita.it/mcp`.

## Strumenti MCP

| Strumento | Cosa restituisce |
|---|---|
| `presenta_knowita` | Profilo istituzionale: storia, purpose, numeri, certificazioni, pubblicazioni e business unit. |
| `metodo_execution_way` | Il metodo Execution Way©: livelli, 7 pilastri e aree di servizio collegate. |
| `trova_servizi` | I servizi Knowità adatti a un'esigenza descritta a parole, ad esempio "introdurre gli OKR" o "bilancio di sostenibilità". |
| `execution_radar` | Gli Execution Radar, il sistema di autovalutazione basato sul metodo Execution Way©. |
| `cerca_contenuti` | Articoli, approfondimenti e casi di successo pubblicati su knowita.it. |
| `eventi_knowita` | Il calendario eventi dell'anno: Executive Club, sessioni executive online e masterclass, con l'archivio dal 2010. |
| `ultime_news` | Le news più recenti del Content Hub. |
| `contatti_knowita` | I recapiti ufficiali. |

## Execution Radar in anteprima

Per ricevere in anteprima gli Execution Radar, scrivete a **info@knowita.it**.

## Struttura

Il server è un plugin WordPress, indipendente dal tema del sito.

- `knowita-ai.php`: gestisce i percorsi `/mcp` e `/llms.txt`, l'API REST e i dati schema.org.
- `includes/data.php`: fonte unica dei contenuti.
- `includes/class-core.php`: ricerca dei servizi e generazione di llms.txt e schema.org.
- `includes/class-mcp.php`: protocollo MCP (JSON-RPC 2.0).
- `server.json`: scheda per il registro ufficiale MCP (`it.knowita/execution-way`).

---

© Knowità s.r.l., Arezzo – riproduzione riservata · [www.knowita.it](https://www.knowita.it)
