# Plan de Migration - Cyber City 2034 vers Nuxt 4 + PostgreSQL

## Table des matières
1. [Vue d'ensemble du projet](#1-vue-densemble-du-projet)
2. [Architecture cible](#2-architecture-cible)
3. [Phase 1 : Préparation et analyse](#phase-1--préparation-et-analyse)
4. [Phase 2 : Migration de la base de données](#phase-2--migration-de-la-base-de-données)
5. [Phase 3 : Création de l'API Backend](#phase-3--création-de-lapi-backend)
6. [Phase 4 : Développement Frontend Nuxt 4](#phase-4--développement-frontend-nuxt-4)
7. [Phase 5 : Migration des fonctionnalités](#phase-5--migration-des-fonctionnalités)
8. [Phase 6 : Tests et déploiement](#phase-6--tests-et-déploiement)
9. [Ressources et bonnes pratiques](#ressources-et-bonnes-pratiques)

---

## 1. Vue d'ensemble du projet

### 1.1 État actuel du projet

**Cyber City 2034** est un jeu de rôle web basé sur :
- **Backend** : PHP 5.x/7.x avec architecture semi-MVC
- **Base de données** : MySQL avec préfixe `cc_`
- **Frontend** : Prototype.js + JavaScript vanilla
- **Templates** : Twig 2.0
- **Session** : Sessions PHP natives

**Fonctionnalités principales identifiées :**
- Système d'authentification multi-personnages
- Gestion de personnages (stats, compétences, inventaire)
- Système de combat (armes blanches, armes à feu, mains nues)
- Déplacement dans la ville (lieux, zones)
- Économie (argent, banque, transactions, boutiques)
- Communication (radios cryptées, téléphones)
- Système de messages (HE - Historique d'événements)
- Gestion MJ (maître du jeu)
- Intégration forum

### 1.2 Objectifs de la migration

**Pourquoi réécrire ?**
- Moderniser la stack technologique
- Améliorer les performances
- Faciliter la maintenance
- Meilleure expérience utilisateur (SPA réactive)
- Architecture scalable et maintenable
- Sécurité renforcée

**Stack cible :**
- **Frontend** : Nuxt 4 (Vue 3 + SSR/SSG)
- **Backend** : API REST/GraphQL (Node.js ou autre)
- **Base de données** : PostgreSQL 15+
- **Auth** : JWT + Refresh tokens
- **ORM** : Prisma ou Drizzle
- **State management** : Pinia
- **Styling** : TailwindCSS

---

## 2. Architecture cible

### 2.1 Architecture globale

```
┌─────────────────────────────────────────┐
│         Frontend (Nuxt 4)               │
│  - Pages/Components Vue 3               │
│  - Pinia stores (state management)      │
│  - Composables                          │
│  - TailwindCSS                          │
└──────────────┬──────────────────────────┘
               │ HTTP/WebSocket
               ▼
┌─────────────────────────────────────────┐
│       API Backend (Node.js/Nitro)       │
│  - Routes API REST                      │
│  - Middleware auth                      │
│  - Business logic                       │
│  - Validation (Zod)                     │
└──────────────┬──────────────────────────┘
               │ SQL
               ▼
┌─────────────────────────────────────────┐
│         PostgreSQL 15+                  │
│  - Tables normalisées                   │
│  - Relations                            │
│  - Triggers/Functions                   │
└─────────────────────────────────────────┘
```

### 2.2 Structure des dossiers Nuxt 4

```
cyber-city-2034/
├── .nuxt/                    # Build artifacts (auto-généré)
├── assets/                   # Assets non compilés (SCSS, images)
│   ├── css/
│   │   └── main.css
│   └── images/
├── components/               # Composants Vue réutilisables
│   ├── auth/
│   │   ├── LoginForm.vue
│   │   └── RegisterForm.vue
│   ├── character/
│   │   ├── CharacterCard.vue
│   │   ├── CharacterStats.vue
│   │   └── CharacterInventory.vue
│   ├── game/
│   │   ├── Map.vue
│   │   ├── Combat.vue
│   │   └── ActionPanel.vue
│   ├── ui/
│   │   ├── Button.vue
│   │   ├── Modal.vue
│   │   └── Toast.vue
│   └── layout/
│       ├── Header.vue
│       ├── Sidebar.vue
│       └── Footer.vue
├── composables/              # Composition API helpers
│   ├── useAuth.ts
│   ├── useCharacter.ts
│   ├── useInventory.ts
│   ├── useCombat.ts
│   └── useWebSocket.ts
├── layouts/                  # Layouts Nuxt
│   ├── default.vue
│   ├── game.vue
│   └── admin.vue
├── middleware/               # Middlewares de route
│   ├── auth.ts
│   ├── guest.ts
│   └── gm.ts               # Game Master only
├── pages/                    # Pages (routing auto)
│   ├── index.vue            # Page d'accueil
│   ├── login.vue
│   ├── register.vue
│   ├── characters/
│   │   ├── index.vue        # Liste personnages
│   │   ├── create.vue
│   │   └── [id].vue         # Détail personnage
│   ├── game/
│   │   ├── index.vue        # Vue principale du jeu
│   │   ├── map.vue
│   │   ├── inventory.vue
│   │   └── combat.vue
│   ├── bank/
│   │   └── index.vue
│   ├── shop/
│   │   └── [id].vue
│   └── admin/               # Zone MJ
│       └── index.vue
├── plugins/                  # Plugins Nuxt
│   ├── api.ts
│   └── toast.ts
├── public/                   # Fichiers statiques
│   ├── favicon.ico
│   └── robots.txt
├── server/                   # API Backend (Nitro)
│   ├── api/                 # Routes API
│   │   ├── auth/
│   │   │   ├── login.post.ts
│   │   │   ├── register.post.ts
│   │   │   └── logout.post.ts
│   │   ├── characters/
│   │   │   ├── index.get.ts
│   │   │   ├── [id].get.ts
│   │   │   ├── create.post.ts
│   │   │   └── [id].patch.ts
│   │   ├── game/
│   │   │   ├── move.post.ts
│   │   │   ├── action.post.ts
│   │   │   └── combat.post.ts
│   │   ├── inventory/
│   │   │   └── [...].ts
│   │   └── bank/
│   │       └── [...].ts
│   ├── middleware/          # Middleware serveur
│   │   └── auth.ts
│   ├── utils/               # Utilitaires backend
│   │   ├── db.ts
│   │   ├── jwt.ts
│   │   └── validators.ts
│   └── plugins/
│       └── database.ts
├── stores/                   # Pinia stores
│   ├── auth.ts
│   ├── character.ts
│   ├── game.ts
│   └── ui.ts
├── types/                    # TypeScript types
│   ├── api.ts
│   ├── character.ts
│   ├── game.ts
│   └── database.ts
├── .env                      # Variables d'environnement
├── nuxt.config.ts           # Configuration Nuxt
├── package.json
├── tsconfig.json
├── tailwind.config.ts
└── README.md
```

---

## Phase 1 : Préparation et analyse

### Étape 1.1 : Analyse de la base de données existante

**Objectif** : Comprendre le schéma MySQL actuel

**Actions :**
1. Exporter le schéma de la base de données
```bash
mysqldump -u ccv4 -p --no-data cybercity2034_v4 > schema.sql
```

2. Analyser les tables principales :
   - `cc_account` : Comptes utilisateurs
   - `cc_perso` : Personnages
   - `cc_session` : Sessions
   - `cc_item` : Items du jeu
   - `cc_lieu` : Lieux/zones
   - `cc_he` : Historique d'événements (messages)
   - Tables de relation (inventaire, équipement, etc.)

3. Créer un diagramme ERD (Entity Relationship Diagram)
   - Utiliser un outil comme dbdiagram.io ou draw.io
   - Identifier toutes les relations (1-1, 1-N, N-N)
   - Noter les contraintes et index

4. Documenter les points d'attention :
   - Encodage des caractères (UTF-8)
   - Champs obsolètes ou inutilisés
   - Colonnes à renommer pour plus de clarté
   - Types de données à optimiser

**Livrable** : Document `database-analysis.md` avec le schéma et les notes

### Étape 1.2 : Audit du code PHP

**Objectif** : Inventorier les fonctionnalités et la logique métier

**Actions :**
1. Lister toutes les classes dans `/classes`
```bash
find classes/ -name "*.php" | sort
```

2. Cartographier les fonctionnalités par catégorie :
   - **Auth** : Login, Register, Session
   - **Character** : CRUD personnages, stats, compétences
   - **Combat** : Logique de combat
   - **Inventory** : Gestion inventaire/équipement
   - **Movement** : Déplacements
   - **Economy** : Banque, boutiques, transactions
   - **Communication** : Messages, radios, téléphones
   - **Admin/MJ** : Fonctionnalités MJ

3. Extraire la logique métier cruciale :
   - Formules de calcul (dégâts, réussite, etc.)
   - Règles du jeu
   - Validations métier

4. Identifier les dépendances externes :
   - Forum (phpBB/SMF ?)
   - Email
   - Sessions

**Livrable** : Document `features-inventory.md`

### Étape 1.3 : Setup de l'environnement de développement

**Objectif** : Préparer les outils de développement

**Actions :**
1. Installer les prérequis :
```bash
# Node.js 20+ (via nvm recommandé)
nvm install 20
nvm use 20

# PostgreSQL 15+
# Sur Ubuntu/Debian :
sudo apt install postgresql postgresql-contrib

# Vérifier les versions
node --version  # v20.x.x
npm --version   # 10.x.x
psql --version  # PostgreSQL 15+
```

2. Créer le dépôt Git pour le nouveau projet :
```bash
mkdir cyber-city-2034-nuxt
cd cyber-city-2034-nuxt
git init
```

3. Initialiser le projet Nuxt 4 :
```bash
npx nuxi@latest init .
# Choisir les options :
# - Package manager: npm
# - TypeScript: Yes
```

4. Installer les dépendances essentielles :
```bash
# UI et styling
npm install -D @nuxtjs/tailwindcss
npm install @headlessui/vue @heroicons/vue

# State management
npm install pinia @pinia/nuxt

# Validation
npm install zod

# Database (choisir selon backend)
npm install drizzle-orm postgres  # OU prisma
npm install -D drizzle-kit

# Auth
npm install jose  # Pour JWT

# Dev tools
npm install -D @nuxtjs/eslint-config-typescript
```

5. Configuration initiale de `nuxt.config.ts` :
```typescript
export default defineNuxtConfig({
  devtools: { enabled: true },

  modules: [
    '@nuxtjs/tailwindcss',
    '@pinia/nuxt',
  ],

  runtimeConfig: {
    // Private keys (serveur uniquement)
    jwtSecret: process.env.JWT_SECRET,
    databaseUrl: process.env.DATABASE_URL,

    // Public keys (client + serveur)
    public: {
      apiBase: process.env.API_BASE_URL || '/api',
    }
  },

  typescript: {
    strict: true,
    typeCheck: true,
  },

  compatibilityDate: '2024-10-20',
})
```

6. Créer le fichier `.env` :
```env
# Database
DATABASE_URL="postgresql://user:password@localhost:5432/cybercity2034"

# JWT
JWT_SECRET="votre-secret-ultra-securise-changez-moi"
JWT_EXPIRES_IN="15m"
REFRESH_TOKEN_EXPIRES_IN="7d"

# App
NODE_ENV="development"
API_BASE_URL="http://localhost:3000/api"
```

**Livrable** : Environnement de dev opérationnel

---

## Phase 2 : Migration de la base de données

### Étape 2.1 : Conception du schéma PostgreSQL

**Objectif** : Moderniser et optimiser le schéma de données

**Principes à suivre :**
- Normalisation (3NF minimum)
- Nommage cohérent (snake_case pour PostgreSQL)
- Types de données appropriés
- Contraintes et index optimisés
- Utilisation des fonctionnalités PostgreSQL (JSONB, Arrays, Enums, etc.)

**Exemple de migration d'une table :**

**Avant (MySQL) :**
```sql
CREATE TABLE cc_perso (
  id int(11) NOT NULL AUTO_INCREMENT,
  userid int(11) NOT NULL,
  nom varchar(50) NOT NULL,
  playertype varchar(20) DEFAULT 'humain',
  bloque tinyint(1) DEFAULT 0,
  inscription_valide tinyint(1) DEFAULT 0,
  -- ... autres champs
  PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
```

**Après (PostgreSQL) :**
```sql
-- Créer un type ENUM pour playertype
CREATE TYPE player_type AS ENUM ('humain', 'pnj', 'bot');

-- Table modernisée
CREATE TABLE characters (
  id SERIAL PRIMARY KEY,
  user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
  name VARCHAR(50) NOT NULL,
  player_type player_type DEFAULT 'humain',
  is_blocked BOOLEAN DEFAULT false,
  is_validated BOOLEAN DEFAULT false,

  -- Métadonnées
  created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,

  -- Index
  CONSTRAINT characters_name_unique UNIQUE (name)
);

-- Index pour les recherches fréquentes
CREATE INDEX idx_characters_user_id ON characters(user_id);
CREATE INDEX idx_characters_player_type ON characters(player_type) WHERE player_type = 'humain';

-- Trigger pour updated_at
CREATE OR REPLACE FUNCTION update_updated_at_column()
RETURNS TRIGGER AS $$
BEGIN
  NEW.updated_at = CURRENT_TIMESTAMP;
  RETURN NEW;
END;
$$ language 'plpgsql';

CREATE TRIGGER update_characters_updated_at
  BEFORE UPDATE ON characters
  FOR EACH ROW
  EXECUTE FUNCTION update_updated_at_column();
```

**Améliorations apportées :**
- Noms de tables en anglais et au pluriel
- Noms de colonnes descriptifs (`is_blocked` au lieu de `bloque`)
- Type ENUM pour les valeurs fixes
- Timestamps avec timezone
- Trigger automatique pour `updated_at`
- Contraintes de clé étrangère avec CASCADE
- Index optimisés

### Étape 2.2 : Créer le schéma complet avec Drizzle ORM

**Objectif** : Définir le schéma en TypeScript avec Drizzle

**Créer** `server/database/schema.ts` :

```typescript
import { pgTable, serial, varchar, text, timestamp, boolean, integer, pgEnum, index } from 'drizzle-orm/pg-core';
import { relations } from 'drizzle-orm';

// ========================================
// ENUMS
// ========================================

export const playerTypeEnum = pgEnum('player_type', ['humain', 'pnj', 'bot']);
export const weaponTypeEnum = pgEnum('weapon_type', ['melee', 'firearm', 'unarmed']);
export const itemCategoryEnum = pgEnum('item_category', ['weapon', 'armor', 'consumable', 'tool', 'misc']);

// ========================================
// TABLES
// ========================================

// Users (comptes)
export const users = pgTable('users', {
  id: serial('id').primaryKey(),
  email: varchar('email', { length: 255 }).notNull().unique(),
  password: varchar('password', { length: 255 }).notNull(),
  isAdmin: boolean('is_admin').default(false),
  isBlocked: boolean('is_blocked').default(false),
  createdAt: timestamp('created_at').defaultNow(),
  updatedAt: timestamp('updated_at').defaultNow(),
}, (table) => ({
  emailIdx: index('users_email_idx').on(table.email),
}));

// Characters (personnages)
export const characters = pgTable('characters', {
  id: serial('id').primaryKey(),
  userId: integer('user_id').notNull().references(() => users.id, { onDelete: 'cascade' }),
  name: varchar('name', { length: 50 }).notNull().unique(),
  playerType: playerTypeEnum('player_type').default('humain'),

  // Stats
  healthPoints: integer('health_points').default(100),
  maxHealthPoints: integer('max_health_points').default(100),
  actionPoints: integer('action_points').default(50),
  experience: integer('experience').default(0),
  level: integer('level').default(1),

  // Économie
  cash: integer('cash').default(0),

  // Localisation
  currentLocationId: integer('current_location_id').references(() => locations.id),

  // Statut
  isValidated: boolean('is_validated').default(false),
  isBlocked: boolean('is_blocked').default(false),

  createdAt: timestamp('created_at').defaultNow(),
  updatedAt: timestamp('updated_at').defaultNow(),
  lastActivityAt: timestamp('last_activity_at').defaultNow(),
}, (table) => ({
  userIdIdx: index('characters_user_id_idx').on(table.userId),
  nameIdx: index('characters_name_idx').on(table.name),
}));

// Locations (lieux)
export const locations = pgTable('locations', {
  id: serial('id').primaryKey(),
  code: varchar('code', { length: 100 }).notNull().unique(), // ex: "A.douanes.innactifs"
  name: varchar('name', { length: 100 }).notNull(),
  description: text('description'),
  parentLocationId: integer('parent_location_id').references(() => locations.id),

  // Propriétés
  isPublic: boolean('is_public').default(true),
  requiresKey: boolean('requires_key').default(false),

  createdAt: timestamp('created_at').defaultNow(),
  updatedAt: timestamp('updated_at').defaultNow(),
});

// Items (objets)
export const items = pgTable('items', {
  id: serial('id').primaryKey(),
  name: varchar('name', { length: 100 }).notNull(),
  description: text('description'),
  category: itemCategoryEnum('category').notNull(),

  // Stats pour armes
  weaponType: weaponTypeEnum('weapon_type'),
  damage: integer('damage'),
  accuracy: integer('accuracy'),

  // Propriétés générales
  weight: integer('weight').default(1),
  value: integer('value').default(0),
  isStackable: boolean('is_stackable').default(false),
  maxStack: integer('max_stack').default(1),

  createdAt: timestamp('created_at').defaultNow(),
  updatedAt: timestamp('updated_at').defaultNow(),
});

// Character Inventory (inventaire des personnages)
export const characterInventory = pgTable('character_inventory', {
  id: serial('id').primaryKey(),
  characterId: integer('character_id').notNull().references(() => characters.id, { onDelete: 'cascade' }),
  itemId: integer('item_id').notNull().references(() => items.id),
  quantity: integer('quantity').default(1),
  isEquipped: boolean('is_equipped').default(false),

  createdAt: timestamp('created_at').defaultNow(),
  updatedAt: timestamp('updated_at').defaultNow(),
}, (table) => ({
  characterIdIdx: index('char_inv_character_id_idx').on(table.characterId),
  itemIdIdx: index('char_inv_item_id_idx').on(table.itemId),
}));

// Event History (historique d'événements - ancien "HE")
export const eventHistory = pgTable('event_history', {
  id: serial('id').primaryKey(),
  characterId: integer('character_id').notNull().references(() => characters.id, { onDelete: 'cascade' }),
  type: varchar('type', { length: 50 }).notNull(), // 'message', 'combat', 'system', etc.
  content: text('content').notNull(),
  metadata: text('metadata'), // JSON stringifié ou utiliser JSONB
  isRead: boolean('is_read').default(false),
  isDeleted: boolean('is_deleted').default(false),

  createdAt: timestamp('created_at').defaultNow(),
}, (table) => ({
  characterIdIdx: index('event_history_character_id_idx').on(table.characterId),
  createdAtIdx: index('event_history_created_at_idx').on(table.createdAt),
}));

// Bank Accounts (comptes bancaires)
export const bankAccounts = pgTable('bank_accounts', {
  id: serial('id').primaryKey(),
  characterId: integer('character_id').notNull().references(() => characters.id, { onDelete: 'cascade' }),
  accountNumber: varchar('account_number', { length: 50 }).notNull().unique(),
  balance: integer('balance').default(0),

  createdAt: timestamp('created_at').defaultNow(),
  updatedAt: timestamp('updated_at').defaultNow(),
});

// Sessions (pour gérer les sessions utilisateur)
export const sessions = pgTable('sessions', {
  id: serial('id').primaryKey(),
  userId: integer('user_id').notNull().references(() => users.id, { onDelete: 'cascade' }),
  token: varchar('token', { length: 255 }).notNull().unique(),
  refreshToken: varchar('refresh_token', { length: 255 }),
  expiresAt: timestamp('expires_at').notNull(),

  createdAt: timestamp('created_at').defaultNow(),
}, (table) => ({
  tokenIdx: index('sessions_token_idx').on(table.token),
  userIdIdx: index('sessions_user_id_idx').on(table.userId),
}));

// ========================================
// RELATIONS
// ========================================

export const usersRelations = relations(users, ({ many }) => ({
  characters: many(characters),
  sessions: many(sessions),
}));

export const charactersRelations = relations(characters, ({ one, many }) => ({
  user: one(users, {
    fields: [characters.userId],
    references: [users.id],
  }),
  currentLocation: one(locations, {
    fields: [characters.currentLocationId],
    references: [locations.id],
  }),
  inventory: many(characterInventory),
  eventHistory: many(eventHistory),
  bankAccounts: many(bankAccounts),
}));

export const characterInventoryRelations = relations(characterInventory, ({ one }) => ({
  character: one(characters, {
    fields: [characterInventory.characterId],
    references: [characters.id],
  }),
  item: one(items, {
    fields: [characterInventory.itemId],
    references: [items.id],
  }),
}));
```

**Configuration Drizzle** `drizzle.config.ts` :
```typescript
import type { Config } from 'drizzle-kit';

export default {
  schema: './server/database/schema.ts',
  out: './server/database/migrations',
  driver: 'pg',
  dbCredentials: {
    connectionString: process.env.DATABASE_URL!,
  },
} satisfies Config;
```

### Étape 2.3 : Migration des données

**Objectif** : Transférer les données de MySQL vers PostgreSQL

**Option 1 : Script de migration Node.js**

Créer `scripts/migrate-data.ts` :

```typescript
import { drizzle } from 'drizzle-orm/postgres-js';
import postgres from 'postgres';
import mysql from 'mysql2/promise';
import { users, characters, items, locations } from '../server/database/schema';

async function migrateData() {
  // Connexion MySQL source
  const mysqlConn = await mysql.createConnection({
    host: 'localhost',
    user: 'ccv4',
    password: 'passtmp',
    database: 'cybercity2034_v4',
  });

  // Connexion PostgreSQL cible
  const pgClient = postgres(process.env.DATABASE_URL!);
  const db = drizzle(pgClient);

  try {
    console.log('🚀 Début de la migration...');

    // 1. Migrer les utilisateurs
    console.log('📦 Migration des utilisateurs...');
    const [mysqlUsers] = await mysqlConn.query('SELECT * FROM cc_account');

    for (const user of mysqlUsers as any[]) {
      await db.insert(users).values({
        id: user.id,
        email: user.email,
        password: user.password, // Déjà hashé
        isAdmin: user.auth_admin === 1,
        isBlocked: user.bloque === 1,
        createdAt: user.date_inscription || new Date(),
      });
    }
    console.log(`✅ ${mysqlUsers.length} utilisateurs migrés`);

    // 2. Migrer les personnages
    console.log('📦 Migration des personnages...');
    const [mysqlPersos] = await mysqlConn.query('SELECT * FROM cc_perso');

    for (const perso of mysqlPersos as any[]) {
      await db.insert(characters).values({
        id: perso.id,
        userId: perso.userid,
        name: perso.nom,
        playerType: perso.playertype as any,
        healthPoints: perso.pv,
        maxHealthPoints: perso.pvmax,
        actionPoints: perso.pa,
        cash: perso.argent,
        isValidated: perso.inscription_valide === 1,
        isBlocked: perso.bloque === 1,
        lastActivityAt: perso.last_activity || new Date(),
      });
    }
    console.log(`✅ ${mysqlPersos.length} personnages migrés`);

    // 3. Migrer les lieux
    // 4. Migrer les items
    // 5. Migrer l'inventaire
    // ... etc.

    console.log('✅ Migration terminée avec succès !');
  } catch (error) {
    console.error('❌ Erreur lors de la migration:', error);
  } finally {
    await mysqlConn.end();
    await pgClient.end();
  }
}

migrateData();
```

**Option 2 : Utiliser pgLoader (plus rapide pour gros volumes)**

Créer `migration.load` :
```sql
LOAD DATABASE
  FROM mysql://ccv4:passtmp@localhost/cybercity2034_v4
  INTO postgresql://user:pass@localhost/cybercity2034

WITH include drop, create tables, create indexes, reset sequences

SET maintenance_work_mem to '128MB',
    work_mem to '12MB',
    search_path to 'public'

CAST type tinyint to boolean drop typemod using tinyint-to-boolean

BEFORE LOAD DO
  $$ DROP SCHEMA IF EXISTS public CASCADE; $$,
  $$ CREATE SCHEMA public; $$;
```

Exécuter :
```bash
pgloader migration.load
```

**Important** : Après la migration, vérifier :
- Compter les enregistrements (`SELECT COUNT(*) FROM ...`)
- Vérifier l'intégrité référentielle
- Tester quelques requêtes complexes

---

## Phase 3 : Création de l'API Backend

### Étape 3.1 : Structure de l'API

**Objectif** : Créer une API REST propre et sécurisée

**Architecture des routes :**

```
/api
├── /auth
│   ├── POST /login
│   ├── POST /register
│   ├── POST /logout
│   └── POST /refresh
├── /users
│   ├── GET /me
│   └── PATCH /me
├── /characters
│   ├── GET /
│   ├── POST /
│   ├── GET /:id
│   ├── PATCH /:id
│   └── DELETE /:id
├── /game
│   ├── POST /move
│   ├── POST /action
│   ├── POST /combat
│   └── GET /events
├── /inventory
│   ├── GET /:characterId
│   ├── POST /:characterId/equip
│   ├── POST /:characterId/use
│   └── POST /:characterId/drop
├── /bank
│   ├── GET /:characterId/accounts
│   ├── POST /:characterId/transfer
│   └── GET /:characterId/history
└── /admin
    └── ... (routes MJ)
```

### Étape 3.2 : Système d'authentification JWT

**Créer** `server/utils/auth.ts` :

```typescript
import { sign, verify } from 'jose';
import { hash, compare } from 'bcrypt';

const JWT_SECRET = new TextEncoder().encode(
  process.env.JWT_SECRET || 'votre-secret'
);

export interface JWTPayload {
  userId: number;
  email: string;
}

export async function hashPassword(password: string): Promise<string> {
  return hash(password, 10);
}

export async function verifyPassword(
  password: string,
  hashedPassword: string
): Promise<boolean> {
  return compare(password, hashedPassword);
}

export async function generateToken(payload: JWTPayload): Promise<string> {
  return new sign({ ...payload })
    .setProtectedHeader({ alg: 'HS256' })
    .setExpirationTime('15m')
    .setIssuedAt()
    .sign(JWT_SECRET);
}

export async function generateRefreshToken(payload: JWTPayload): Promise<string> {
  return new sign({ ...payload })
    .setProtectedHeader({ alg: 'HS256' })
    .setExpirationTime('7d')
    .setIssuedAt()
    .sign(JWT_SECRET);
}

export async function verifyToken(token: string): Promise<JWTPayload | null> {
  try {
    const { payload } = await verify(token, JWT_SECRET);
    return payload as JWTPayload;
  } catch {
    return null;
  }
}
```

**Middleware d'authentification** `server/middleware/auth.ts` :

```typescript
import { verifyToken } from '../utils/auth';

export default defineEventHandler(async (event) => {
  // Ignorer certaines routes
  const publicRoutes = ['/api/auth/login', '/api/auth/register'];
  if (publicRoutes.some(route => event.path.startsWith(route))) {
    return;
  }

  // Vérifier le token
  const authHeader = getHeader(event, 'authorization');
  if (!authHeader?.startsWith('Bearer ')) {
    throw createError({
      statusCode: 401,
      message: 'Non authentifié',
    });
  }

  const token = authHeader.substring(7);
  const payload = await verifyToken(token);

  if (!payload) {
    throw createError({
      statusCode: 401,
      message: 'Token invalide',
    });
  }

  // Stocker l'utilisateur dans le contexte
  event.context.user = payload;
});
```

### Étape 3.3 : Exemple de routes API

**Login** `server/api/auth/login.post.ts` :

```typescript
import { z } from 'zod';
import { eq } from 'drizzle-orm';
import { users } from '~/server/database/schema';
import { useDrizzle } from '~/server/utils/db';
import { verifyPassword, generateToken, generateRefreshToken } from '~/server/utils/auth';

const loginSchema = z.object({
  email: z.string().email(),
  password: z.string().min(6),
});

export default defineEventHandler(async (event) => {
  // Valider le body
  const body = await readBody(event);
  const result = loginSchema.safeParse(body);

  if (!result.success) {
    throw createError({
      statusCode: 400,
      message: 'Données invalides',
      data: result.error.issues,
    });
  }

  const { email, password } = result.data;

  // Vérifier l'utilisateur
  const db = useDrizzle();
  const [user] = await db
    .select()
    .from(users)
    .where(eq(users.email, email))
    .limit(1);

  if (!user || !(await verifyPassword(password, user.password))) {
    throw createError({
      statusCode: 401,
      message: 'Email ou mot de passe incorrect',
    });
  }

  if (user.isBlocked) {
    throw createError({
      statusCode: 403,
      message: 'Votre compte est bloqué',
    });
  }

  // Générer les tokens
  const payload = { userId: user.id, email: user.email };
  const accessToken = await generateToken(payload);
  const refreshToken = await generateRefreshToken(payload);

  return {
    accessToken,
    refreshToken,
    user: {
      id: user.id,
      email: user.email,
      isAdmin: user.isAdmin,
    },
  };
});
```

**Obtenir les personnages** `server/api/characters/index.get.ts` :

```typescript
import { eq } from 'drizzle-orm';
import { characters } from '~/server/database/schema';
import { useDrizzle } from '~/server/utils/db';

export default defineEventHandler(async (event) => {
  const userId = event.context.user.userId;

  const db = useDrizzle();
  const userCharacters = await db
    .select()
    .from(characters)
    .where(eq(characters.userId, userId));

  return userCharacters;
});
```

**Créer un personnage** `server/api/characters/create.post.ts` :

```typescript
import { z } from 'zod';
import { characters } from '~/server/database/schema';
import { useDrizzle } from '~/server/utils/db';

const createCharacterSchema = z.object({
  name: z.string().min(3).max(50),
});

export default defineEventHandler(async (event) => {
  const userId = event.context.user.userId;
  const body = await readBody(event);

  const result = createCharacterSchema.safeParse(body);
  if (!result.success) {
    throw createError({
      statusCode: 400,
      message: 'Données invalides',
      data: result.error.issues,
    });
  }

  const db = useDrizzle();

  const [newCharacter] = await db
    .insert(characters)
    .values({
      userId,
      name: result.data.name,
      healthPoints: 100,
      maxHealthPoints: 100,
      actionPoints: 50,
      cash: 100, // Argent de départ
    })
    .returning();

  return newCharacter;
});
```

---

## Phase 4 : Développement Frontend Nuxt 4

### Étape 4.1 : Configuration de base

**TailwindCSS** - Déjà installé avec `@nuxtjs/tailwindcss`

Créer `tailwind.config.ts` :
```typescript
import type { Config } from 'tailwindcss';

export default {
  content: [
    './components/**/*.{js,vue,ts}',
    './layouts/**/*.vue',
    './pages/**/*.vue',
    './plugins/**/*.{js,ts}',
    './app.vue',
  ],
  theme: {
    extend: {
      colors: {
        cyber: {
          dark: '#0a0e27',
          blue: '#00d4ff',
          purple: '#b026ff',
          pink: '#ff006e',
        },
      },
    },
  },
  plugins: [],
} satisfies Config;
```

Créer `assets/css/main.css` :
```css
@tailwind base;
@tailwind components;
@tailwind utilities;

@layer base {
  body {
    @apply bg-cyber-dark text-white;
  }
}

@layer components {
  .btn-primary {
    @apply bg-cyber-blue hover:bg-cyber-blue/80 text-cyber-dark font-bold py-2 px-4 rounded transition;
  }

  .btn-secondary {
    @apply bg-cyber-purple hover:bg-cyber-purple/80 text-white font-bold py-2 px-4 rounded transition;
  }

  .card {
    @apply bg-gray-800 border border-gray-700 rounded-lg p-4;
  }
}
```

### Étape 4.2 : Store Pinia pour l'authentification

**Créer** `stores/auth.ts` :

```typescript
import { defineStore } from 'pinia';

interface User {
  id: number;
  email: string;
  isAdmin: boolean;
}

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null as User | null,
    accessToken: null as string | null,
    refreshToken: null as string | null,
  }),

  getters: {
    isAuthenticated: (state) => !!state.accessToken,
    isAdmin: (state) => state.user?.isAdmin ?? false,
  },

  actions: {
    async login(email: string, password: string) {
      try {
        const response = await $fetch('/api/auth/login', {
          method: 'POST',
          body: { email, password },
        });

        this.user = response.user;
        this.accessToken = response.accessToken;
        this.refreshToken = response.refreshToken;

        // Stocker dans localStorage
        if (process.client) {
          localStorage.setItem('accessToken', response.accessToken);
          localStorage.setItem('refreshToken', response.refreshToken);
        }

        return true;
      } catch (error) {
        console.error('Erreur de connexion:', error);
        return false;
      }
    },

    async logout() {
      this.user = null;
      this.accessToken = null;
      this.refreshToken = null;

      if (process.client) {
        localStorage.removeItem('accessToken');
        localStorage.removeItem('refreshToken');
      }

      await navigateTo('/login');
    },

    async checkAuth() {
      if (process.client) {
        const token = localStorage.getItem('accessToken');
        if (token) {
          this.accessToken = token;
          // Optionnel : vérifier la validité et récupérer l'utilisateur
          try {
            const user = await $fetch('/api/users/me', {
              headers: {
                Authorization: `Bearer ${token}`,
              },
            });
            this.user = user;
          } catch {
            this.logout();
          }
        }
      }
    },
  },
});
```

### Étape 4.3 : Composables utilitaires

**Créer** `composables/useApi.ts` :

```typescript
import { useAuthStore } from '~/stores/auth';

export const useApi = () => {
  const authStore = useAuthStore();

  const apiFetch = $fetch.create({
    baseURL: '/api',
    onRequest({ options }) {
      if (authStore.accessToken) {
        options.headers = {
          ...options.headers,
          Authorization: `Bearer ${authStore.accessToken}`,
        };
      }
    },
    onResponseError({ response }) {
      if (response.status === 401) {
        authStore.logout();
      }
    },
  });

  return { apiFetch };
};
```

**Créer** `composables/useCharacter.ts` :

```typescript
export const useCharacter = () => {
  const { apiFetch } = useApi();

  const getCharacters = async () => {
    return await apiFetch('/characters');
  };

  const createCharacter = async (name: string) => {
    return await apiFetch('/characters/create', {
      method: 'POST',
      body: { name },
    });
  };

  const getCharacter = async (id: number) => {
    return await apiFetch(`/characters/${id}`);
  };

  return {
    getCharacters,
    createCharacter,
    getCharacter,
  };
};
```

### Étape 4.4 : Pages principales

**Page de login** `pages/login.vue` :

```vue
<template>
  <div class="min-h-screen flex items-center justify-center">
    <div class="card max-w-md w-full">
      <h1 class="text-3xl font-bold mb-6 text-cyber-blue">
        Cyber City 2034
      </h1>

      <form @submit.prevent="handleLogin" class="space-y-4">
        <div>
          <label class="block text-sm font-medium mb-2">Email</label>
          <input
            v-model="email"
            type="email"
            required
            class="w-full px-3 py-2 bg-gray-900 border border-gray-700 rounded focus:outline-none focus:border-cyber-blue"
          />
        </div>

        <div>
          <label class="block text-sm font-medium mb-2">Mot de passe</label>
          <input
            v-model="password"
            type="password"
            required
            class="w-full px-3 py-2 bg-gray-900 border border-gray-700 rounded focus:outline-none focus:border-cyber-blue"
          />
        </div>

        <button type="submit" class="btn-primary w-full" :disabled="loading">
          {{ loading ? 'Connexion...' : 'Se connecter' }}
        </button>

        <p v-if="error" class="text-red-500 text-sm">{{ error }}</p>
      </form>

      <p class="mt-4 text-center text-sm text-gray-400">
        Pas encore de compte ?
        <NuxtLink to="/register" class="text-cyber-blue hover:underline">
          S'inscrire
        </NuxtLink>
      </p>
    </div>
  </div>
</template>

<script setup lang="ts">
const authStore = useAuthStore();
const router = useRouter();

const email = ref('');
const password = ref('');
const loading = ref(false);
const error = ref('');

definePageMeta({
  middleware: 'guest',
});

const handleLogin = async () => {
  loading.value = true;
  error.value = '';

  const success = await authStore.login(email.value, password.value);

  if (success) {
    router.push('/characters');
  } else {
    error.value = 'Email ou mot de passe incorrect';
  }

  loading.value = false;
};
</script>
```

**Liste des personnages** `pages/characters/index.vue` :

```vue
<template>
  <div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold text-cyber-blue">Mes Personnages</h1>
      <button @click="showCreateModal = true" class="btn-primary">
        + Créer un personnage
      </button>
    </div>

    <div v-if="loading" class="text-center py-12">
      Chargement...
    </div>

    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div
        v-for="character in characters"
        :key="character.id"
        class="card hover:border-cyber-blue cursor-pointer transition"
        @click="selectCharacter(character.id)"
      >
        <h3 class="text-xl font-bold mb-2">{{ character.name }}</h3>
        <div class="space-y-1 text-sm text-gray-400">
          <p>Niveau {{ character.level }}</p>
          <p>PV: {{ character.healthPoints }} / {{ character.maxHealthPoints }}</p>
          <p>PA: {{ character.actionPoints }}</p>
          <p class="text-cyber-blue">{{ character.cash }} Cr</p>
        </div>
      </div>
    </div>

    <!-- Modal création -->
    <Modal v-model="showCreateModal" title="Créer un personnage">
      <form @submit.prevent="handleCreate" class="space-y-4">
        <div>
          <label class="block text-sm font-medium mb-2">Nom du personnage</label>
          <input
            v-model="newCharacterName"
            type="text"
            required
            minlength="3"
            maxlength="50"
            class="w-full px-3 py-2 bg-gray-900 border border-gray-700 rounded"
          />
        </div>
        <button type="submit" class="btn-primary w-full">
          Créer
        </button>
      </form>
    </Modal>
  </div>
</template>

<script setup lang="ts">
const { getCharacters, createCharacter } = useCharacter();
const router = useRouter();

definePageMeta({
  middleware: 'auth',
});

const characters = ref([]);
const loading = ref(true);
const showCreateModal = ref(false);
const newCharacterName = ref('');

onMounted(async () => {
  characters.value = await getCharacters();
  loading.value = false;
});

const selectCharacter = (id: number) => {
  router.push(`/game?character=${id}`);
};

const handleCreate = async () => {
  await createCharacter(newCharacterName.value);
  showCreateModal.value = false;
  newCharacterName.value = '';
  // Recharger la liste
  characters.value = await getCharacters();
};
</script>
```

### Étape 4.5 : Middleware de route

**Middleware auth** `middleware/auth.ts` :

```typescript
export default defineNuxtRouteMiddleware((to, from) => {
  const authStore = useAuthStore();

  if (!authStore.isAuthenticated) {
    return navigateTo('/login');
  }
});
```

**Middleware guest** `middleware/guest.ts` :

```typescript
export default defineNuxtRouteMiddleware((to, from) => {
  const authStore = useAuthStore();

  if (authStore.isAuthenticated) {
    return navigateTo('/characters');
  }
});
```

---

## Phase 5 : Migration des fonctionnalités

### Étape 5.1 : Système d'inventaire

**Backend** - Route pour obtenir l'inventaire :

```typescript
// server/api/inventory/[characterId].get.ts
import { eq } from 'drizzle-orm';
import { characterInventory, items } from '~/server/database/schema';
import { useDrizzle } from '~/server/utils/db';

export default defineEventHandler(async (event) => {
  const characterId = parseInt(event.context.params.characterId);
  const userId = event.context.user.userId;

  // Vérifier que le personnage appartient à l'utilisateur
  // ... (vérification omise pour la brièveté)

  const db = useDrizzle();

  const inventory = await db
    .select({
      id: characterInventory.id,
      itemId: characterInventory.itemId,
      quantity: characterInventory.quantity,
      isEquipped: characterInventory.isEquipped,
      item: items,
    })
    .from(characterInventory)
    .leftJoin(items, eq(characterInventory.itemId, items.id))
    .where(eq(characterInventory.characterId, characterId));

  return inventory;
});
```

**Frontend** - Composant inventaire :

```vue
<!-- components/game/Inventory.vue -->
<template>
  <div class="card">
    <h2 class="text-2xl font-bold mb-4">Inventaire</h2>

    <div class="grid grid-cols-4 gap-2">
      <div
        v-for="slot in inventory"
        :key="slot.id"
        class="aspect-square border border-gray-700 rounded p-2 hover:border-cyber-blue cursor-pointer"
        @click="selectItem(slot)"
      >
        <div class="text-xs">{{ slot.item?.name }}</div>
        <div v-if="slot.quantity > 1" class="text-xs text-gray-400">
          x{{ slot.quantity }}
        </div>
        <div v-if="slot.isEquipped" class="text-xs text-green-500">
          Équipé
        </div>
      </div>

      <!-- Slots vides -->
      <div
        v-for="i in emptySlots"
        :key="`empty-${i}`"
        class="aspect-square border border-gray-700 rounded"
      />
    </div>

    <!-- Actions sur l'item sélectionné -->
    <div v-if="selectedItem" class="mt-4 p-4 border-t border-gray-700">
      <h3 class="font-bold">{{ selectedItem.item?.name }}</h3>
      <p class="text-sm text-gray-400 mt-2">
        {{ selectedItem.item?.description }}
      </p>
      <div class="mt-4 flex gap-2">
        <button
          v-if="!selectedItem.isEquipped"
          @click="equipItem"
          class="btn-primary"
        >
          Équiper
        </button>
        <button
          @click="useItem"
          class="btn-secondary"
        >
          Utiliser
        </button>
        <button
          @click="dropItem"
          class="btn-secondary"
        >
          Jeter
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
const props = defineProps<{
  characterId: number;
}>();

const { apiFetch } = useApi();

const inventory = ref([]);
const selectedItem = ref(null);
const maxSlots = 20;

const emptySlots = computed(() => {
  return Math.max(0, maxSlots - inventory.value.length);
});

onMounted(async () => {
  await loadInventory();
});

const loadInventory = async () => {
  inventory.value = await apiFetch(`/inventory/${props.characterId}`);
};

const selectItem = (item: any) => {
  selectedItem.value = item;
};

const equipItem = async () => {
  await apiFetch(`/inventory/${props.characterId}/equip`, {
    method: 'POST',
    body: { inventoryId: selectedItem.value.id },
  });
  await loadInventory();
};

const useItem = async () => {
  // Logique d'utilisation
};

const dropItem = async () => {
  // Logique pour jeter
};
</script>
```

### Étape 5.2 : Système de combat

**Logique métier backend** `server/utils/combat.ts` :

```typescript
interface CombatResult {
  success: boolean;
  damage: number;
  message: string;
  attackerHp: number;
  defenderHp: number;
}

export function calculateCombatResult(
  attacker: any,
  defender: any,
  weapon: any
): CombatResult {
  // Formules de combat (à adapter selon vos règles)
  const baseAccuracy = 50;
  const weaponAccuracy = weapon?.accuracy || 0;
  const totalAccuracy = baseAccuracy + weaponAccuracy;

  // Jet de réussite
  const roll = Math.random() * 100;
  const success = roll <= totalAccuracy;

  if (!success) {
    return {
      success: false,
      damage: 0,
      message: 'Attaque ratée !',
      attackerHp: attacker.healthPoints,
      defenderHp: defender.healthPoints,
    };
  }

  // Calcul des dégâts
  const baseDamage = weapon?.damage || 5;
  const damageVariation = Math.floor(Math.random() * 10) - 5;
  const totalDamage = Math.max(1, baseDamage + damageVariation);

  const newDefenderHp = Math.max(0, defender.healthPoints - totalDamage);

  return {
    success: true,
    damage: totalDamage,
    message: `Touché ! ${totalDamage} points de dégâts.`,
    attackerHp: attacker.healthPoints,
    defenderHp: newDefenderHp,
  };
}
```

**Route de combat** `server/api/game/combat.post.ts` :

```typescript
import { z } from 'zod';
import { eq } from 'drizzle-orm';
import { characters, characterInventory } from '~/server/database/schema';
import { useDrizzle } from '~/server/utils/db';
import { calculateCombatResult } from '~/server/utils/combat';

const combatSchema = z.object({
  attackerId: z.number(),
  defenderId: z.number(),
  weaponId: z.number().optional(),
});

export default defineEventHandler(async (event) => {
  const body = await readBody(event);
  const result = combatSchema.safeParse(body);

  if (!result.success) {
    throw createError({ statusCode: 400, message: 'Données invalides' });
  }

  const { attackerId, defenderId, weaponId } = result.data;

  const db = useDrizzle();

  // Charger l'attaquant et le défenseur
  const [attacker] = await db
    .select()
    .from(characters)
    .where(eq(characters.id, attackerId))
    .limit(1);

  const [defender] = await db
    .select()
    .from(characters)
    .where(eq(characters.id, defenderId))
    .limit(1);

  if (!attacker || !defender) {
    throw createError({ statusCode: 404, message: 'Personnage introuvable' });
  }

  // Vérifier les PA
  if (attacker.actionPoints < 2) {
    throw createError({ statusCode: 400, message: 'Pas assez de PA' });
  }

  // Charger l'arme
  let weapon = null;
  if (weaponId) {
    // ... charger l'arme depuis l'inventaire
  }

  // Calculer le résultat
  const combatResult = calculateCombatResult(attacker, defender, weapon);

  // Mettre à jour les PV et PA
  await db
    .update(characters)
    .set({
      actionPoints: attacker.actionPoints - 2,
    })
    .where(eq(characters.id, attackerId));

  await db
    .update(characters)
    .set({
      healthPoints: combatResult.defenderHp,
    })
    .where(eq(characters.id, defenderId));

  // Ajouter au HE (Event History)
  // ...

  return combatResult;
});
```

### Étape 5.3 : Système de déplacement

**Route de déplacement** `server/api/game/move.post.ts` :

```typescript
import { z } from 'zod';
import { eq } from 'drizzle-orm';
import { characters, locations } from '~/server/database/schema';
import { useDrizzle } from '~/server/utils/db';

const moveSchema = z.object({
  characterId: z.number(),
  destinationId: z.number(),
});

export default defineEventHandler(async (event) => {
  const body = await readBody(event);
  const result = moveSchema.safeParse(body);

  if (!result.success) {
    throw createError({ statusCode: 400, message: 'Données invalides' });
  }

  const { characterId, destinationId } = result.data;

  const db = useDrizzle();

  // Charger le personnage
  const [character] = await db
    .select()
    .from(characters)
    .where(eq(characters.id, characterId))
    .limit(1);

  if (!character) {
    throw createError({ statusCode: 404, message: 'Personnage introuvable' });
  }

  // Vérifier les PA
  const moveCost = 1;
  if (character.actionPoints < moveCost) {
    throw createError({ statusCode: 400, message: 'Pas assez de PA' });
  }

  // Vérifier que la destination existe et est accessible
  const [destination] = await db
    .select()
    .from(locations)
    .where(eq(locations.id, destinationId))
    .limit(1);

  if (!destination) {
    throw createError({ statusCode: 404, message: 'Lieu introuvable' });
  }

  // TODO: Vérifier les restrictions d'accès (clés, permissions, etc.)

  // Effectuer le déplacement
  await db
    .update(characters)
    .set({
      currentLocationId: destinationId,
      actionPoints: character.actionPoints - moveCost,
      lastActivityAt: new Date(),
    })
    .where(eq(characters.id, characterId));

  return {
    success: true,
    newLocation: destination,
    remainingAP: character.actionPoints - moveCost,
  };
});
```

---

## Phase 6 : Tests et déploiement

### Étape 6.1 : Tests unitaires

**Installer Vitest** :
```bash
npm install -D vitest @vue/test-utils happy-dom
```

**Configuration** `vitest.config.ts` :
```typescript
import { defineConfig } from 'vitest/config';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
  plugins: [vue()],
  test: {
    environment: 'happy-dom',
  },
});
```

**Exemple de test** `tests/auth.test.ts` :
```typescript
import { describe, it, expect } from 'vitest';
import { hashPassword, verifyPassword } from '~/server/utils/auth';

describe('Auth Utils', () => {
  it('should hash password correctly', async () => {
    const password = 'test123';
    const hashed = await hashPassword(password);

    expect(hashed).not.toBe(password);
    expect(hashed.length).toBeGreaterThan(0);
  });

  it('should verify password correctly', async () => {
    const password = 'test123';
    const hashed = await hashPassword(password);

    const isValid = await verifyPassword(password, hashed);
    expect(isValid).toBe(true);

    const isInvalid = await verifyPassword('wrong', hashed);
    expect(isInvalid).toBe(false);
  });
});
```

### Étape 6.2 : Variables d'environnement pour production

**Créer** `.env.production` :
```env
DATABASE_URL="postgresql://user:pass@prod-server:5432/cybercity2034"
JWT_SECRET="secret-production-ultra-securise-minimum-32-caracteres"
NODE_ENV="production"
NUXT_PUBLIC_API_BASE_URL="https://cybercity2034.com/api"
```

### Étape 6.3 : Build et déploiement

**Build pour production** :
```bash
npm run build
```

**Déploiement sur serveur Node.js** :
```bash
# Sur le serveur
node .output/server/index.mjs
```

**Ou avec PM2** :
```bash
pm2 start .output/server/index.mjs --name cybercity2034
pm2 save
pm2 startup
```

**Configuration Nginx** :
```nginx
server {
    listen 80;
    server_name cybercity2034.com;

    location / {
        proxy_pass http://localhost:3000;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection 'upgrade';
        proxy_set_header Host $host;
        proxy_cache_bypass $http_upgrade;
    }
}
```

---

## Ressources et bonnes pratiques

### Documentation officielle

- **Nuxt 4** : https://nuxt.com/docs
- **Vue 3** : https://vuejs.org/guide/
- **Pinia** : https://pinia.vuejs.org/
- **Drizzle ORM** : https://orm.drizzle.team/
- **TailwindCSS** : https://tailwindcss.com/docs
- **PostgreSQL** : https://www.postgresql.org/docs/

### Bonnes pratiques

#### Sécurité
- ✅ Toujours valider les données côté serveur (Zod)
- ✅ Hacher les mots de passe (bcrypt)
- ✅ Utiliser HTTPS en production
- ✅ Implémenter rate limiting
- ✅ Protéger contre CSRF, XSS, SQL Injection
- ✅ Ne jamais exposer les clés secrètes

#### Performance
- ✅ Utiliser des index PostgreSQL
- ✅ Mettre en cache les requêtes fréquentes
- ✅ Lazy loading des composants
- ✅ Code splitting
- ✅ Optimiser les images

#### Code
- ✅ TypeScript strict activé
- ✅ Commenter le code complexe
- ✅ Noms de variables explicites
- ✅ Fonctions courtes et ciblées
- ✅ Tests unitaires critiques
- ✅ Git commits atomiques

### Ordre recommandé d'implémentation

1. **Fondations** (Semaine 1-2)
   - Setup projet Nuxt 4
   - Migration base de données
   - Authentification JWT
   - Pages login/register

2. **Personnages** (Semaine 3)
   - CRUD personnages
   - Sélection personnage
   - Vue stats

3. **Jeu de base** (Semaine 4-5)
   - Système de déplacement
   - Inventaire
   - Carte de la ville

4. **Interactions** (Semaine 6-7)
   - Combat
   - Messages/HE
   - Commerce

5. **Économie** (Semaine 8)
   - Banque
   - Boutiques
   - Transactions

6. **Avancé** (Semaine 9-10)
   - Communication (radios, téléphones)
   - Items spéciaux
   - Zone MJ

7. **Finitions** (Semaine 11-12)
   - Tests
   - Optimisations
   - Documentation
   - Déploiement

---

## Conclusion

Ce plan vous guide étape par étape dans la migration complète de Cyber City 2034 vers une stack moderne. N'hésitez pas à :

- **Prendre votre temps** : Chaque étape est importante
- **Tester régulièrement** : Ne passez pas à l'étape suivante sans tester
- **Documenter vos choix** : Gardez trace de vos décisions
- **Demander de l'aide** : Si vous bloquez, je suis là !

**Bon courage dans cette migration ! 🚀**
