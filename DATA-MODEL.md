# 📊 DATA MODEL - Maria Cavalheiro CRM

## **Versão:** Produção Atual (crm.html)
## **Data:** 17/01/2026

---

## 🗂️ **ENTIDADES E RELACIONAMENTOS**

```
┌─────────────┐       ┌─────────────┐       ┌─────────────┐
│   CLIENTE   │──────<│   PEDIDO    │>──────│  ITEM_PEDIDO│
└─────────────┘       └─────────────┘       └─────────────┘
                             │
                             │
                      ┌──────▼──────┐
                      │ REPRESENTANTE│
                      └─────────────┘
```

---

## 📋 **1. TABELA: CLIENTES**

### **Campos:**

| Campo | Tipo | Obrigatório | Descrição |
|-------|------|-------------|-----------|
| `id` | string (UUID) | ✅ | ID único do cliente |
| `nome` | string | ✅ | Nome da loja/cliente |
| `nome_completo` | string | ⬜ | Nome completo (se diferente) |
| `telefone` | string | ✅ | Telefone principal |
| `whatsapp` | string | ⬜ | WhatsApp (pode ser igual ao telefone) |
| `email` | string | ⬜ | Email do cliente |
| `cpf_cnpj` | string | ⬜ | CPF ou CNPJ |
| `endereco` | string | ⬜ | Endereço completo |
| `cidade` | string | ⬜ | Cidade |
| `estado` | string | ⬜ | UF (2 letras) |
| `cep` | string | ⬜ | CEP |
| `vendedora_selecionada` | string | ⬜ | Nome da representante (Daia/Ariane) |
| `representante` | string | ⬜ | Alias para vendedora_selecionada |
| `lead_status` | string | ⬜ | Status no funil (lead/negociacao/confirmado) |
| `observacoes` | string | ⬜ | Notas sobre o cliente |
| `created_at` | timestamp | ✅ | Data de criação (auto) |
| `updated_at` | timestamp | ✅ | Data de atualização (auto) |

### **Exemplo JSON:**

```json
{
  "id": "abc123-def456-ghi789",
  "nome": "Boutique Elegance",
  "nome_completo": "Boutique Elegance Moda Feminina LTDA",
  "telefone": "(11) 98765-4321",
  "whatsapp": "(11) 98765-4321",
  "email": "contato@elegance.com.br",
  "cpf_cnpj": "12.345.678/0001-99",
  "endereco": "Rua das Flores, 123",
  "cidade": "São Paulo",
  "estado": "SP",
  "cep": "01234-567",
  "vendedora_selecionada": "Daia",
  "representante": "Daia",
  "lead_status": "confirmado",
  "observacoes": "Cliente VIP, sempre compra coleções completas",
  "created_at": 1705520400000,
  "updated_at": 1705606800000
}
```

---

## 📦 **2. TABELA: PEDIDOS**

### **Campos:**

| Campo | Tipo | Obrigatório | Descrição |
|-------|------|-------------|-----------|
| `id` | string (UUID) | ✅ | ID único do pedido |
| `cliente_id` | string | ✅ | ID do cliente (FK) |
| `cliente_nome` | string | ✅ | Nome do cliente (denormalizado) |
| `vendedora_selecionada` | string | ✅ | Representante responsável |
| `representante` | string | ⬜ | Alias para vendedora_selecionada |
| `origem` | string | ✅ | Origem do pedido (anuncio/recompra) |
| `status` | string | ✅ | Status operacional do pedido |
| `faturado` | boolean | ✅ | Se pedido foi faturado (true/false) |
| `valor_total` | number | ✅ | Valor SOLICITADO pelo cliente |
| `valor_faturado` | number | ⬜ | Valor REALMENTE FATURADO |
| `quantidade_total` | number | ✅ | Quantidade SOLICITADA |
| `quantidade_faturada` | number | ⬜ | Quantidade REALMENTE FATURADA |
| `forma_pagamento` | string | ⬜ | Pix/Boleto/Cartão/etc |
| `tipo_cliente` | string | ⬜ | Atacado/Revenda/etc |
| `observacoes` | string | ⬜ | Notas sobre o pedido |
| `data_pedido` | string | ✅ | Data do pedido (formato ISO) |
| `data_faturamento` | string | ⬜ | Data do faturamento |
| `created_at` | timestamp | ✅ | Data de criação (auto) |
| `updated_at` | timestamp | ✅ | Data de atualização (auto) |

### **Status Possíveis:**

```javascript
// Operacionais (não afetam relatórios)
"Recebido"
"Pendente"
"Aguardando Pagamento"
"Em Produção"
"Em Separação"
"Aguardando Envio"
"Enviado"
"Entregue"
"Cancelado"
```

### **Exemplo JSON:**

```json
{
  "id": "ped-2024-001",
  "cliente_id": "abc123-def456-ghi789",
  "cliente_nome": "Boutique Elegance",
  "vendedora_selecionada": "Daia",
  "representante": "Daia",
  "origem": "anuncio",
  "status": "Entregue",
  "faturado": true,
  "valor_total": 5240.00,
  "valor_faturado": 5240.00,
  "quantidade_total": 18,
  "quantidade_faturada": 18,
  "forma_pagamento": "Pix",
  "tipo_cliente": "Atacado",
  "observacoes": "Cliente pediu entrega urgente",
  "data_pedido": "2024-01-15T10:30:00Z",
  "data_faturamento": "2024-01-16T14:00:00Z",
  "created_at": 1705320600000,
  "updated_at": 1705414800000
}
```

---

## 🛍️ **3. TABELA: ITENS_PEDIDO (Futuro)**

### **Campos:**

| Campo | Tipo | Obrigatório | Descrição |
|-------|------|-------------|-----------|
| `id` | string (UUID) | ✅ | ID único do item |
| `pedido_id` | string | ✅ | ID do pedido (FK) |
| `produto_codigo` | string | ✅ | Código do produto (VT7133, etc) |
| `produto_nome` | string | ✅ | Nome do produto |
| `quantidade_solicitada` | number | ✅ | Qtd solicitada |
| `quantidade_faturada` | number | ⬜ | Qtd realmente faturada |
| `valor_unitario` | number | ✅ | Preço unitário |
| `valor_total` | number | ✅ | Valor total do item |
| `created_at` | timestamp | ✅ | Data de criação (auto) |

### **Exemplo JSON:**

```json
{
  "id": "item-001",
  "pedido_id": "ped-2024-001",
  "produto_codigo": "VT7133",
  "produto_nome": "Vestido Longo Floral",
  "quantidade_solicitada": 5,
  "quantidade_faturada": 5,
  "valor_unitario": 120.00,
  "valor_total": 600.00,
  "created_at": 1705320600000
}
```

---

## 👤 **4. TABELA: USUARIOS (Futuro SaaS)**

### **Campos:**

| Campo | Tipo | Obrigatório | Descrição |
|-------|------|-------------|-----------|
| `id` | string (UUID) | ✅ | ID único do usuário |
| `nome` | string | ✅ | Nome completo |
| `email` | string | ✅ | Email (login) |
| `senha_hash` | string | ✅ | Senha criptografada |
| `perfil` | string | ✅ | Admin/Representante/Caixa |
| `representante_nome` | string | ⬜ | Nome da rep (se perfil=Representante) |
| `ativo` | boolean | ✅ | Se usuário está ativo |
| `ultimo_acesso` | timestamp | ⬜ | Último login |
| `created_at` | timestamp | ✅ | Data de criação |
| `updated_at` | timestamp | ✅ | Data de atualização |

### **Perfis:**

```javascript
"Admin"         // Acesso total
"Representante" // Acesso restrito aos próprios pedidos
"Caixa"         // Acesso apenas para faturamento
```

### **Exemplo JSON:**

```json
{
  "id": "user-001",
  "nome": "Daia Silva",
  "email": "daia@mariacavalheiro.com.br",
  "senha_hash": "$2b$10$abcdefghijklmnopqrstuvwxyz123456",
  "perfil": "Representante",
  "representante_nome": "Daia",
  "ativo": true,
  "ultimo_acesso": 1705606800000,
  "created_at": 1705520400000,
  "updated_at": 1705606800000
}
```

---

## 💰 **5. TABELA: COMISSOES (Calculado, não persistido)**

### **Estrutura de Cálculo:**

```javascript
{
  "representante": "Daia",
  "total_vendas": 34152.00,
  "percentual_comissao": 0.05,  // 5%
  "valor_comissao": 1707.60,
  "periodo": "2024-01",
  "pedidos_faturados": 6
}
```

### **Regra:**
- **5% sobre valor_faturado** dos pedidos onde `faturado=true`
- Comissão calculada em tempo real no CRM
- Não é persistida no banco (calculada sob demanda)

---

## 📊 **6. RELATÓRIO: QUEBRA DE CAIXA (Calculado)**

### **Estrutura:**

```javascript
{
  "total_faturamento": 44582.00,
  "percentual_quebra": 0.01,  // 1%
  "valor_quebra": 445.82,
  "periodo": "2024-01"
}
```

### **Regra:**
- **1% sobre faturamento total** dos pedidos onde `faturado=true`
- Calculado em tempo real
- Representa perdas operacionais esperadas

---

## 🔑 **RELACIONAMENTOS**

### **1. Cliente → Pedidos (1:N)**
```javascript
cliente.id === pedido.cliente_id
```

### **2. Pedido → Itens (1:N) - Futuro**
```javascript
pedido.id === item_pedido.pedido_id
```

### **3. Representante → Pedidos (1:N)**
```javascript
usuario.representante_nome === pedido.vendedora_selecionada
```

---

## 📈 **MÉTRICAS CALCULADAS**

### **Dashboard:**

```javascript
{
  "total_pedidos": COUNT(pedidos),
  "pedidos_faturados": COUNT(pedidos WHERE faturado=true),
  "total_vendas": SUM(valor_faturado WHERE faturado=true),
  "total_pecas": SUM(quantidade_faturada WHERE faturado=true)
}
```

### **Funil de Vendas:**

```javascript
{
  "leads": COUNT(clientes WHERE lead_status LIKE '%lead%'),
  "negociacao": COUNT(clientes WHERE lead_status LIKE '%negociacao%'),
  "confirmados": COUNT(clientes WHERE lead_status LIKE '%confirmado%' OR '%concluido%')
}
```

### **Representante:**

```javascript
{
  "total_pedidos": COUNT(pedidos WHERE vendedora_selecionada = nome),
  "pedidos_faturados": COUNT(pedidos WHERE vendedora_selecionada = nome AND faturado=true),
  "total_vendas": SUM(valor_faturado WHERE vendedora_selecionada = nome AND faturado=true),
  "total_pecas": SUM(quantidade_faturada WHERE vendedora_selecionada = nome AND faturado=true),
  "comissao": total_vendas * 0.05
}
```

---

## 🗃️ **CAMPOS DO SISTEMA (Auto-gerenciados)**

Todos os registros têm:

```javascript
{
  "gs_project_id": "maria-cavalheiro-crm",  // ID do projeto
  "gs_table_name": "pedidos",               // Nome da tabela
  "created_at": 1705520400000,              // Timestamp de criação
  "updated_at": 1705606800000,              // Timestamp de atualização
  "deleted": false                           // Soft delete flag
}
```

---

## 📝 **NOTAS IMPORTANTES**

### **1. Valores Solicitado vs Faturado:**
- `valor_total` / `quantidade_total`: O que o cliente PEDIU
- `valor_faturado` / `quantidade_faturada`: O que REALMENTE foi entregue/cobrado
- **Relatórios usam SEMPRE valores faturados** (não solicitados)

### **2. Status vs Faturado:**
- `status`: Operacional (para acompanhamento)
- `faturado`: Financeiro (para relatórios)
- Status pode ser "Entregue" mas `faturado=false` (se não foi pago)

### **3. Origem:**
- `anuncio`: Cliente novo vindo de anúncio/marketing
- `recompra`: Cliente antigo fazendo nova compra

### **4. Representantes:**
- Atualmente: Daia e Ariane
- Valores possíveis: "Daia", "Ariane", "Admin"
- Case-insensitive no código

---

## 🔄 **FLUXO DE DADOS**

```
1. Cliente faz pedido → tables/pedidos (POST)
2. Pedido com faturado=false
3. Status muda conforme operação
4. Quando pago → faturado=true
5. CRM calcula relatórios baseado em faturado=true
6. Comissões calculadas em tempo real
```

---

## 🎯 **PRÓXIMOS PASSOS PARA SAAS**

### **Entidades a Adicionar:**

1. **usuarios** - Autenticação e perfis
2. **itens_pedido** - Detalhamento de produtos
3. **produtos** - Catálogo de produtos
4. **comissoes** - Histórico de comissões pagas
5. **audit_log** - Log de mudanças
6. **configuracoes** - Configurações do sistema

### **Campos a Adicionar:**

1. **tenant_id** - Para multi-tenancy (múltiplas empresas)
2. **meta_data** - Campos customizáveis por cliente
3. **tags** - Tags para organização
4. **arquivos** - Anexos (notas fiscais, comprovantes)

---

**DATA MODEL COMPLETO!**

**Versão:** Produção Atual  
**Status:** ✅ Documentado  
**Próximo:** BUSINESS-RULES.md
