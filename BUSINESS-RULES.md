# 📜 BUSINESS RULES - Maria Cavalheiro CRM

## **Versão:** Produção Atual (crm.html)
## **Data:** 17/01/2026

---

## 🎯 **REGRAS DE NEGÓCIO FUNDAMENTAIS**

---

## 1️⃣ **ORIGEM DO PEDIDO**

### **Conceito:**
Identifica de onde veio o cliente.

### **Valores Possíveis:**

```javascript
"anuncio"  // Cliente novo vindo de marketing/anúncios
"recompra" // Cliente antigo fazendo nova compra
```

### **Regras:**

✅ **Anúncio:**
- Primeiro pedido do cliente
- Veio de campanha de marketing
- Conta para métricas de CAC (Custo de Aquisição de Cliente)

✅ **Recompra:**
- Cliente já comprou antes
- Retornou para nova compra
- Indica fidelização

### **Impacto:**
- **Financeiro:** Nenhum (não afeta cálculos)
- **Relatórios:** Permite análise de CAC vs LTV
- **Comissões:** Nenhum (comissão igual para ambos)

---

## 2️⃣ **SOLICITADO vs FATURADO**

### **Conceito:**
O que o cliente PEDIU vs o que foi REALMENTE entregue/cobrado.

### **Campos:**

```javascript
// SOLICITADO (o que o cliente pediu)
valor_total: 5000.00
quantidade_total: 20

// FATURADO (o que realmente aconteceu)
valor_faturado: 4800.00
quantidade_faturada: 19
```

### **Por que podem ser diferentes:**

1. **Estoque insuficiente**
   - Cliente pediu 20 peças
   - Só tinha 19 em estoque
   - Faturou 19

2. **Mudança de pedido**
   - Cliente desistiu de alguns itens
   - Cancelou parte do pedido

3. **Erro de cadastro**
   - Valor digitado errado
   - Corrigido no faturamento

4. **Negociação**
   - Cliente negociou desconto
   - Valor final menor

### **REGRA FUNDAMENTAL:**

```
⚠️ TODOS os relatórios usam VALORES FATURADOS!
⚠️ NUNCA use valor_total para relatórios financeiros!
```

### **Implementação:**

```javascript
// ❌ ERRADO
const totalVendas = pedidos.reduce((sum, p) => sum + p.valor_total, 0);

// ✅ CORRETO
const totalVendas = pedidos
  .filter(p => p.faturado === true)
  .reduce((sum, p) => sum + parseFloat(p.valor_faturado || p.valor_total || 0), 0);
```

---

## 3️⃣ **STATUS DO PEDIDO**

### **Conceito:**
Status OPERACIONAL do pedido (para acompanhamento, NÃO financeiro).

### **Valores Possíveis:**

```javascript
// Em ordem cronológica típica:
"Recebido"              // Pedido acabou de chegar
"Pendente"              // Aguardando algo (pagamento, confirmação, etc)
"Aguardando Pagamento"  // Cliente ainda não pagou
"Em Produção"           // Pedido sendo produzido
"Em Separação"          // Produto sendo separado para envio
"Aguardando Envio"      // Pronto para enviar
"Enviado"               // Já foi despachado
"Entregue"              // Cliente recebeu
"Cancelado"             // Pedido cancelado
```

### **REGRA FUNDAMENTAL:**

```
⚠️ Status NÃO define se pedido conta nos relatórios!
⚠️ Use o campo "faturado" para isso!
```

### **Exemplos:**

```javascript
// Pedido entregue MAS não pago
{
  status: "Entregue",
  faturado: false  // ← NÃO conta nos relatórios
}

// Pedido ainda em separação MAS já pago
{
  status: "Em Separação",
  faturado: true  // ← JÁ conta nos relatórios
}
```

### **Fluxo Típico:**

```
Recebido → Aguardando Pagamento → Em Produção → 
Em Separação → Aguardando Envio → Enviado → Entregue
```

### **Quando marcar faturado=true:**

```
✅ Quando o cliente PAGOU (independente do status)
✅ Quando o pedido foi CONFIRMADO financeiramente
✅ Quando pode entrar nos relatórios de vendas
```

---

## 4️⃣ **FATURAMENTO (Campo faturado)**

### **Conceito:**
Define se pedido entra nos relatórios financeiros.

### **Valores:**

```javascript
faturado: true   // Pedido CONFIRMADO, entra nos relatórios
faturado: false  // Pedido PENDENTE, NÃO entra nos relatórios
```

### **REGRA DE OURO:**

```
✅ faturado = true  → CONTA nos relatórios
❌ faturado = false → NÃO CONTA nos relatórios
```

### **Quando marcar true:**

1. ✅ Cliente pagou (Pix/Boleto/Cartão confirmado)
2. ✅ Pedido confirmado financeiramente
3. ✅ Valor pode entrar no faturamento do mês
4. ✅ Comissão pode ser calculada

### **Quando manter false:**

1. ❌ Aguardando pagamento
2. ❌ Pedido ainda não confirmado
3. ❌ Cliente cancelou
4. ❌ Erro de cadastro (duplicado, teste, etc)

### **Impacto:**

```javascript
// Dashboard
totalVendas = SUM(valor_faturado WHERE faturado=true)

// Comissões
comissao = totalVendas * 0.05

// Quebra de Caixa
quebraCaixa = totalVendas * 0.01
```

---

## 5️⃣ **COMISSÕES DAS REPRESENTANTES**

### **Regra:**

```
Comissão = 5% do valor faturado
```

### **Cálculo:**

```javascript
// Para cada representante
const pedidosFaturados = pedidos.filter(p => 
  p.faturado === true && 
  p.vendedora_selecionada === nomeRepresentante
);

const totalVendas = pedidosFaturados.reduce((sum, p) => 
  sum + parseFloat(p.valor_faturado || p.valor_total || 0), 0
);

const comissao = totalVendas * 0.05;  // 5%
```

### **Exemplo:**

```javascript
// Daia vendeu R$ 34.152,00 (faturado)
comissao = 34152.00 * 0.05 = R$ 1.707,60
```

### **Regras Específicas:**

1. ✅ **Baseado em valor_faturado** (não solicitado)
2. ✅ **Apenas pedidos com faturado=true**
3. ✅ **5% fixo** (não varia por tipo de produto/cliente)
4. ✅ **Calculado em tempo real** (não persistido)
5. ✅ **Independe da origem** (anúncio ou recompra)
6. ✅ **Independe do status** (pode estar "Em Produção" mas já pago)

### **Não gera comissão:**

❌ Pedidos com `faturado=false`  
❌ Pedidos cancelados (mesmo se foi faturado antes)  
❌ Valores solicitados (apenas faturados contam)

---

## 6️⃣ **QUEBRA DE CAIXA (1%)**

### **Conceito:**
Perdas operacionais esperadas (devoluções, descontos, erros, etc).

### **Regra:**

```
Quebra de Caixa = 1% do faturamento total
```

### **Cálculo:**

```javascript
const totalFaturamento = pedidos
  .filter(p => p.faturado === true)
  .reduce((sum, p) => sum + parseFloat(p.valor_faturado || p.valor_total || 0), 0);

const quebraCaixa = totalFaturamento * 0.01;  // 1%
```

### **Exemplo:**

```javascript
// Faturamento total: R$ 44.582,00
quebraCaixa = 44582.00 * 0.01 = R$ 445,82
```

### **O que representa:**

- 📦 **Devoluções** (clientes devolveram produtos)
- 💔 **Produtos com defeito** (trocas, descontos)
- 📉 **Descontos** (negociações pós-venda)
- 🔄 **Erros operacionais** (envio errado, etc)
- 💸 **Perdas diversas** (frete não cobrado, etc)

### **Uso:**

```javascript
// Faturamento bruto
R$ 44.582,00

// Quebra de caixa (1%)
- R$ 445,82

// Faturamento líquido
= R$ 44.136,18
```

### **Regras:**

1. ✅ **1% fixo** sobre faturamento
2. ✅ **Não é abatido das comissões** (comissão sobre bruto)
3. ✅ **Calculado apenas sobre faturado=true**
4. ✅ **Serve para provisão financeira**

---

## 7️⃣ **PEDIDO FECHADO (Definição)**

### **Conceito:**
Quando um pedido é considerado "fechado" para relatórios.

### **Regra:**

```
Pedido Fechado = faturado === true
```

### **NÃO É:**

❌ Status = "Entregue"  
❌ Status = "Finalizado"  
❌ Data de entrega preenchida  
❌ Cliente disse que recebeu

### **É:**

✅ Campo `faturado` marcado como `true`  
✅ Pagamento confirmado  
✅ Valor entra nos relatórios

### **Fluxo:**

```
1. Pedido criado → faturado: false
2. Cliente faz pedido → faturado: false
3. Cliente PAGA → faturado: true ✅
4. Status muda para "Entregue" → faturado continua: true
```

### **Casos Especiais:**

```javascript
// Caso 1: Entregue mas não pago
{
  status: "Entregue",
  faturado: false
}
// → NÃO conta como fechado

// Caso 2: Em separação mas já pago
{
  status: "Em Separação",
  faturado: true
}
// → JÁ conta como fechado ✅

// Caso 3: Cancelado
{
  status: "Cancelado",
  faturado: false
}
// → NÃO conta como fechado
```

---

## 8️⃣ **REPRESENTANTES (Daia e Ariane)**

### **Conceito:**
Cada pedido tem uma representante responsável.

### **Valores Possíveis:**

```javascript
"Daia"
"Ariane"
"Admin"  // Pedidos sem representante definida
null     // Pedidos antigos sem atribuição
```

### **Campos:**

```javascript
{
  vendedora_selecionada: "Daia",  // Campo principal
  representante: "Daia"           // Alias (compatibilidade)
}
```

### **Regras:**

1. ✅ **Case-insensitive** no código (`toLowerCase()`)
2. ✅ **Comissão individual** por representante
3. ✅ **Filtros independentes** no CRM
4. ✅ **Acesso restrito** (cada uma vê apenas seus pedidos)

### **Matching:**

```javascript
// Aceita variações
"Daia" === "daia" === "DAIA"
"Ariane" === "ariane" === "ARIANE"
```

---

## 9️⃣ **HIERARQUIA DE PERFIS**

### **Admin:**

```
✅ Vê TODOS os pedidos
✅ Vê TODAS as comissões
✅ Pode alterar faturado=true/false
✅ Pode ver quebra de caixa
✅ Acesso total aos relatórios
```

### **Representante (Daia/Ariane):**

```
✅ Vê apenas SEUS pedidos
❌ NÃO vê pedidos da outra rep
❌ NÃO vê comissões globais
❌ NÃO vê quebra de caixa
✅ Pode alterar status operacional
❌ NÃO pode alterar faturado
```

### **Caixa (Futuro):**

```
✅ Vê apenas pedidos pendentes
✅ Pode marcar faturado=true
❌ NÃO vê comissões
❌ NÃO vê relatórios gerenciais
```

---

## 🔟 **FORMAS DE PAGAMENTO**

### **Valores Possíveis:**

```javascript
"Pix"
"Boleto"
"Cartão de Crédito"
"Cartão de Débito"
"Transferência"
"Dinheiro"
"Cheque"
```

### **Uso:**

- 📊 **Relatórios:** Análise por forma de pagamento
- 💰 **Financeiro:** Controle de recebíveis
- 📈 **Estratégia:** Identificar forma mais usada

### **Regra:**

```
⚠️ Forma de pagamento NÃO afeta comissão
⚠️ Comissão é sempre 5% independente da forma
```

---

## 1️⃣1️⃣ **TIPO DE CLIENTE**

### **Valores Possíveis:**

```javascript
"Atacado"
"Revenda"
"Loja Física"
"E-commerce"
"Representante"
"Consumidor Final"
```

### **Uso:**

- 📊 **Segmentação:** Análise por tipo
- 💼 **Estratégia:** Focar em tipo mais rentável
- 📈 **Relatórios:** Performance por segmento

### **Regra:**

```
⚠️ Tipo de cliente NÃO afeta comissão
⚠️ Comissão é sempre 5% para todos
```

---

## 📊 **RESUMO DAS REGRAS DE CÁLCULO**

### **Total de Vendas:**

```javascript
SUM(valor_faturado) WHERE faturado = true
```

### **Comissão Representante:**

```javascript
SUM(valor_faturado) WHERE faturado = true AND vendedora = nome
* 0.05
```

### **Quebra de Caixa:**

```javascript
SUM(valor_faturado) WHERE faturado = true
* 0.01
```

### **Total de Pedidos:**

```javascript
COUNT(*) WHERE faturado = true
```

### **Total de Peças:**

```javascript
SUM(quantidade_faturada) WHERE faturado = true
```

---

## ⚠️ **REGRAS CRÍTICAS**

### **1. SEMPRE use faturado=true para relatórios**

```javascript
// ❌ ERRADO
const pedidos = allPedidos.filter(p => p.status === "Entregue");

// ✅ CORRETO
const pedidos = allPedidos.filter(p => p.faturado === true);
```

### **2. SEMPRE use valor_faturado (não valor_total)**

```javascript
// ❌ ERRADO
const total = pedidos.reduce((sum, p) => sum + p.valor_total, 0);

// ✅ CORRETO
const total = pedidos.reduce((sum, p) => 
  sum + parseFloat(p.valor_faturado || p.valor_total || 0), 0
);
```

### **3. Status é APENAS operacional**

```
Status serve para:
✅ Acompanhar pedido
✅ Saber onde está na operação
❌ NÃO usar para relatórios financeiros
```

---

## 🎯 **CASOS DE USO**

### **Caso 1: Cliente fez pedido mas não pagou**

```javascript
{
  cliente: "Boutique XYZ",
  valor_total: 5000.00,
  status: "Aguardando Pagamento",
  faturado: false
}
// → NÃO conta nos relatórios
// → NÃO gera comissão
```

### **Caso 2: Cliente pagou, pedido em produção**

```javascript
{
  cliente: "Loja ABC",
  valor_faturado: 3000.00,
  status: "Em Produção",
  faturado: true
}
// → JÁ conta nos relatórios ✅
// → JÁ gera comissão ✅
```

### **Caso 3: Cliente pediu 20, entregou 18**

```javascript
{
  cliente: "Magazine 123",
  valor_total: 2000.00,
  quantidade_total: 20,
  valor_faturado: 1800.00,
  quantidade_faturada: 18,
  faturado: true
}
// → Relatórios usam R$ 1.800,00 (não R$ 2.000,00)
// → Comissão sobre R$ 1.800,00
```

---

**BUSINESS RULES COMPLETO!**

**Versão:** Produção Atual  
**Status:** ✅ Documentado  
**Próximo:** PERMISSIONS-FLOWS.md
