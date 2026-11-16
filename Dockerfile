# Knowità – server MCP (Execution Way©)
# Il server è remoto e pubblico: https://www.knowita.it/mcp (Streamable HTTP, sola lettura).
# Questo container fa solo da ponte stdio -> HTTP per i client e le directory
# che avviano i server MCP in locale (es. Glama).
FROM node:22-alpine
RUN npm install -g mcp-remote@0.16.6 && npm cache clean --force
USER node
ENTRYPOINT ["mcp-remote", "https://www.knowita.it/mcp", "--transport", "http-only"]
