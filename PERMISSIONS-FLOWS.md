# 👥 PERMISSIONS & FLOWS - CRM Maria Cavalheiro

> **Sistema de Controle de Acesso e Fluxos de Tela**  
> Versão: 8.4  
> Data: 17/01/2026

---

## 📊 **PERFIS DE USUÁRIO**

### **1. ADMIN (Administrador)**

**Descrição**: Acesso total ao sistema. Visualiza todos os dados, todas as representantes, e pode gerenciar comissões.

**Permissões**:
- ✅ Ver Dashboard completo com totais globais
- ✅ Ver todos os pedidos (todas as representantes)
- ✅ Ver todos os clientes (todas as representantes)
- ✅ Acessar relatórios consolidados
- ✅ Visualizar funil de vendas completo
- ✅ Acessar páginas de ambas representantes (Daia e Ariane)
- ✅ Calcular e visualizar comissões de todas
- ✅ Editar status de pedidos
- ✅ Editar dados de clientes
- ✅ Aplicar filtros por representante
- ✅ Exportar relatórios

**Identificação no Sistema**:
```javascript
// sessionStorage vazio ou null = Admin
sessionStorage.getItem('mariacavalheiro_vendedora') === null
// OU
sessionStorage.getItem('mariacavalheiro_vendedora') === ''
```

---

### **2. REPRESENTANTE (Daia ou Ariane)**

**Descrição**: Acesso restrito aos próprios dados. Visualiza apenas pedidos, clientes e comissões da própria carteira.

**Permissões**:
- ✅ Ver Dashboard com dados filtrados (apenas seus pedidos)
- ✅ Ver apenas pedidos onde `vendedora_selecionada` ou `representante` = seu nome
- ✅ Ver apenas clientes da sua carteira
- ✅ Visualizar funil de vendas (apenas seus clientes)
- ✅ Acessar apenas sua própria página de representante
- ✅ Visualizar suas comissões
- ❌ **NÃO** pode ver dados de outras representantes
- ❌ **NÃO** pode acessar relatórios consolidados
- ❌ **NÃO** pode editar status de pedidos
- ❌ **NÃO** pode editar dados de clientes

**Identificação no Sistema**:
```javascript
// Representante Daia
sessionStorage.getItem('mariacavalheiro_vendedora') === 'Daia'

// Representante Ariane
sessionStorage.getItem('mariacavalheiro_vendedora') === 'Ariane'
```

**Exemplo de Filtragem**:
```javascript
// Filtrar pedidos da representante
const pedidosRepresentante = pedidos.filter(p => {
    const vendedora = (p.vendedora_selecionada || p.representante || '').toLowerCase();
    return vendedora.includes(nomeRepresentante.toLowerCase());
});
```

---

### **3. CAIXA (Futuro)**

**Descrição**: Perfil para gestão financeira. Visualiza apenas dados de faturamento e comissões.

**Permissões (Planejadas)**:
- ✅ Ver pedidos faturados
- ✅ Calcular comissões
- ✅ Visualizar quebra de caixa (1%)
- ✅ Exportar relatórios financeiros
- ❌ **NÃO** pode ver dados de clientes
- ❌ **NÃO** pode editar pedidos
- ❌ **NÃO** pode acessar funil de vendas

**Status**: 🚧 Não implementado na V8.4

---

## 🗺️ **FLUXO DE TELAS**

### **FLUXO ADMIN**

```
┌─────────────────────────────────────────────────────────┐
│  LOGIN (Futuro)                                          │
│  → sessionStorage.clear()                                │
│  → Redireciona para crm.html                             │
└─────────────────────────────────────────────────────────┘
                      ↓
┌─────────────────────────────────────────────────────────┐
│  DASHBOARD (Padrão)                                      │
│  ┌─────────────────────────────────────────────────┐   │
│  │  📊 TOTAIS GLOBAIS                              │   │
│  │  • 37 Pedidos                                   │   │
│  │  • 8 Faturados                                  │   │
│  │  • R$ 44.582,00 em Vendas                       │   │
│  │  • 201 Peças                                    │   │
│  └─────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────┘
           ↓                    ↓                   ↓
    ┌──────────┐         ┌──────────┐       ┌──────────┐
    │RELATÓRIOS│         │  FUNIL   │       │REP. DAIA │
    └──────────┘         └──────────┘       └──────────┘
         ↓                     ↓                   ↓
    Tabelas +           3 Colunas:          KPIs + Tabela
    Gráficos            Leads (10)          24 pedidos
    Consolidados        Negociação (0)      R$ 34.152
                        Confirmados (19)     Comissão
                        
                        Filtros:
                        [Todas] [Daia] [Ariane]
                        
                        Cards com:
                        • Nome da loja
                        • Telefone
                        • Nº pedidos
                        • Valor total
                        
                        Drag & Drop:
                        Arrasta → Muda Status
                        
                        Modal:
                        Clica card → Detalhes
                                 ↓
                        ┌──────────────────┐
                        │REP. ARIANE       │
                        │KPIs + Tabela     │
                        │13 pedidos        │
                        │R$ 10.430         │
                        └──────────────────┘
```

---

### **FLUXO REPRESENTANTE**

```
┌─────────────────────────────────────────────────────────┐
│  ACESSO DIRETO                                           │
│  → sessionStorage.setItem('...vendedora', 'Daia')       │
│  → Redireciona para crm.html                             │
└─────────────────────────────────────────────────────────┘
                      ↓
┌─────────────────────────────────────────────────────────┐
│  DASHBOARD (Filtrado)                                    │
│  ┌─────────────────────────────────────────────────┐   │
│  │  📊 MEUS DADOS (Daia)                           │   │
│  │  • 24 Pedidos                                   │   │
│  │  • 6 Faturados                                  │   │
│  │  • R$ 34.152,00 em Vendas                       │   │
│  │  • 156 Peças                                    │   │
│  └─────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────┘
           ↓                             ↓
    ┌──────────┐                  ┌──────────┐
    │  FUNIL   │                  │MINHA     │
    │(Filtrado)│                  │PÁGINA    │
    └──────────┘                  └──────────┘
         ↓                             ↓
    Apenas meus                   Representante Daia
    clientes:                     (única acessível)
    Leads (6)                     
    Negociação (0)                KPIs detalhados
    Confirmados (18)              Tabela de pedidos
                                  Comissões
    [!] Relatórios = OCULTO
    [!] Rep. Ariane = OCULTO
```

**Sidebar do Representante**:
```
╔═══════════════════════════════╗
║  MARIA CAVALHEIRO - CRM       ║
╠═══════════════════════════════╣
║  📊 Dashboard                 ║  ✅ Visível
║  📈 Funil de Vendas           ║  ✅ Visível
║  👤 Representante Daia        ║  ✅ Visível
╠═══════════════════════════════╣
║  📋 Relatórios                ║  ❌ OCULTO
║  👤 Representante Ariane      ║  ❌ OCULTO
╚═══════════════════════════════╝
```

---

## 🔐 **MATRIZ DE PERMISSÕES**

| Funcionalidade | Admin | Representante | Caixa (Futuro) |
|----------------|-------|---------------|----------------|
| **Dashboard Global** | ✅ | ❌ | ❌ |
| **Dashboard Filtrado** | ✅ | ✅ | ❌ |
| **Relatórios** | ✅ | ❌ | ✅ |
| **Funil Completo** | ✅ | ❌ | ❌ |
| **Funil Filtrado** | ✅ | ✅ | ❌ |
| **Página Rep. Daia** | ✅ | ✅ (só Daia) | ❌ |
| **Página Rep. Ariane** | ✅ | ✅ (só Ariane) | ❌ |
| **Editar Pedidos** | ✅ | ❌ | ❌ |
| **Editar Clientes** | ✅ | ❌ | ❌ |
| **Calcular Comissões** | ✅ | ✅ (próprias) | ✅ |
| **Ver Quebra de Caixa** | ✅ | ❌ | ✅ |
| **Exportar Dados** | ✅ | ❌ | ✅ |

---

## 🎯 **REGRAS DE CONTROLE DE ACESSO**

### **1. Detecção de Perfil**
```javascript
function detectarRepresentante() {
    const vendedora = sessionStorage.getItem('mariacavalheiro_vendedora');
    
    if (!vendedora || vendedora === '') {
        return 'Admin'; // Acesso total
    }
    
    return vendedora; // 'Daia' ou 'Ariane'
}
```

### **2. Filtragem de Dados**
```javascript
function filtrarPorRepresentante(dados, representante) {
    if (representante === 'Admin') {
        return dados; // Retorna tudo
    }
    
    return dados.filter(item => {
        const vendedora = (item.vendedora_selecionada || item.representante || '').toLowerCase();
        return vendedora.includes(representante.toLowerCase());
    });
}
```

### **3. Controle de Visibilidade da Sidebar**
```javascript
function configurarSidebar(representante) {
    const relatorios = document.getElementById('menu-relatorios');
    const repDaia = document.getElementById('menu-rep-daia');
    const repAriane = document.getElementById('menu-rep-ariane');
    
    if (representante === 'Admin') {
        // Admin vê tudo
        relatorios.style.display = 'block';
        repDaia.style.display = 'block';
        repAriane.style.display = 'block';
    } else if (representante === 'Daia') {
        relatorios.style.display = 'none';
        repDaia.style.display = 'block';
        repAriane.style.display = 'none';
    } else if (representante === 'Ariane') {
        relatorios.style.display = 'none';
        repDaia.style.display = 'none';
        repAriane.style.display = 'block';
    }
}
```

---

## 🔄 **FLUXOS DE NAVEGAÇÃO**

### **1. Dashboard → Funil**
```
Admin:
Dashboard (37 pedidos) → Clica "Funil" → Funil (63 clientes, todos)

Daia:
Dashboard (24 pedidos) → Clica "Funil" → Funil (40 clientes, só dela)
```

### **2. Funil → Modal de Detalhes**
```
1. Clica em card do cliente
2. Modal abre com:
   ├─ Informações do Cliente
   ├─ Lista de Pedidos
   ├─ Histórico
   └─ [Fechar]
3. Clica fora ou [Fechar] → Modal fecha
```

### **3. Dashboard → Representante**
```
Admin:
Dashboard → Clica "Rep. Daia" → Página Daia (24 pedidos, R$ 34.152)
Dashboard → Clica "Rep. Ariane" → Página Ariane (13 pedidos, R$ 10.430)

Daia:
Dashboard → Clica "Representante Daia" → Página Daia (24 pedidos, R$ 34.152)
[!] Menu "Rep. Ariane" NÃO aparece
```

---

## 📱 **RESPONSIVIDADE**

### **Desktop (> 768px)**
- Sidebar fixa à esquerda (250px)
- Área principal com margem-left de 250px
- KPIs em grid de 4 colunas
- Funil com 3 colunas lado a lado

### **Mobile (≤ 768px)**
- Sidebar se transforma em menu hambúrguer
- KPIs em grid de 2 colunas
- Funil com colunas empilhadas (vertical)
- Cards ocupam 100% da largura

---

## 🚀 **PRÓXIMOS PASSOS (Migração SaaS)**

### **1. Autenticação**
```javascript
// Sistema de Login
POST /api/auth/login
{
    "email": "daia@mariacavalheiro.com",
    "senha": "hash_seguro"
}

// Resposta
{
    "token": "jwt_token",
    "perfil": "representante",
    "nome": "Daia",
    "permissoes": ["dashboard", "funil", "minha_pagina"]
}
```

### **2. Multi-tenancy**
```javascript
// Cada empresa tem seu próprio espaço
{
    "tenant_id": "maria_cavalheiro",
    "nome": "Maria Cavalheiro",
    "usuarios": [
        {"nome": "Admin", "perfil": "admin"},
        {"nome": "Daia", "perfil": "representante"},
        {"nome": "Ariane", "perfil": "representante"}
    ]
}
```

### **3. Perfis Customizados**
```json
{
    "perfis": [
        {
            "nome": "gerente_vendas",
            "permissoes": ["dashboard", "relatorios", "funil", "todas_representantes"],
            "pode_editar": false
        },
        {
            "nome": "financeiro",
            "permissoes": ["relatorios", "comissoes"],
            "pode_editar": ["comissoes"]
        }
    ]
}
```

---

## 📊 **TESTES DE VALIDAÇÃO**

### **Teste 1: Acesso Admin**
```javascript
// 1. Limpar sessionStorage
sessionStorage.clear();
location.reload();

// 2. Verificar
console.log('Perfil:', window.CRM.representante); // "Admin"
console.log('Pedidos:', window.CRM.dados.pedidos.length); // 37

// 3. Validar sidebar
// ✅ Todas as 5 abas visíveis
```

### **Teste 2: Acesso Representante Daia**
```javascript
// 1. Simular login como Daia
sessionStorage.setItem('mariacavalheiro_vendedora', 'Daia');
location.reload();

// 2. Verificar
console.log('Perfil:', window.CRM.representante); // "Daia"
console.log('Pedidos:', window.CRM.dados.pedidos.length); // 24 (filtrados)

// 3. Validar sidebar
// ✅ Dashboard visível
// ✅ Funil visível
// ✅ Rep. Daia visível
// ❌ Relatórios OCULTO
// ❌ Rep. Ariane OCULTO
```

### **Teste 3: Navegação Completa (Admin)**
```
1. Dashboard → 37 pedidos visíveis ✅
2. Relatórios → Tabelas e gráficos ✅
3. Funil → 63 clientes em 3 colunas ✅
4. Rep. Daia → 24 pedidos, R$ 34.152 ✅
5. Rep. Ariane → 13 pedidos, R$ 10.430 ✅
```

---

## ✅ **STATUS DA IMPLEMENTAÇÃO**

| Funcionalidade | Status | Observação |
|----------------|--------|------------|
| Detecção de Perfil | ✅ | Implementado em `js/crm.js` |
| Filtragem por Representante | ✅ | Aplicada em todas as views |
| Controle Sidebar | ✅ | Menus ocultos conforme perfil |
| Dashboard Filtrado | ✅ | KPIs calculados por representante |
| Funil Filtrado | ✅ | Botões de filtro + dados filtrados |
| Modal Detalhes | ✅ | 4 seções implementadas |
| Páginas Representantes | ✅ | KPIs + tabelas funcionando |
| Sistema de Login | 🚧 | Planejado para SaaS |
| Multi-tenancy | 🚧 | Planejado para SaaS |
| Perfis Customizados | 🚧 | Planejado para SaaS |

---

**Versão**: 8.4  
**Data**: 17/01/2026  
**Autor**: Sistema CRM Maria Cavalheiro  
**Próxima Revisão**: Migração para GitHub/SaaS
