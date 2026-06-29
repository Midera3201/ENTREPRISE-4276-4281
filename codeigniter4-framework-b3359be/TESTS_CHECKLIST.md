# CHECKLIST DE TESTS BACKEND — Système de Gestion des Congés (CI4)

---

## 1. AUTHENTIFICATION

### 1.1 Login
| # | Test | Méthode | URL | Données attendues | Résultat attendu |
|---|------|---------|-----|-------------------|------------------|
| 1.1.1 | Formulaire de login s'affiche | GET | `/login` | — | Page 200, formulaire avec email/password + CSRF token |
| 1.1.2 | Connexion valide admin | POST | `/login` | `email=admin@techmada.mg` `password=admin123` | Redirection 302 → `/admin`, session créée |
| 1.1.3 | Connexion valide RH | POST | `/login` | `email=rh@techmada.mg` `password=rh12345` | Redirection 302 → `/rh/demandes`, session créée |
| 1.1.4 | Connexion valide employé | POST | `/login` | `email=hery@techmada.mg` `password=employe123` | Redirection 302 → `/employee/dashboard`, session créée |
| 1.1.5 | Email inexistant | POST | `/login` | `email=inexistant@test.com` `password=test` | Redirection 302 → `/login`, flashdata error |
| 1.1.6 | Mot de passe incorrect | POST | `/login` | `email=admin@techmada.mg` `password=wrong` | Redirection 302 → `/login`, flashdata error |
| 1.1.7 | Email vide | POST | `/login` | `email=` `password=test` | Redirection 302, erreur de validation |
| 1.1.8 | Mot de passe < 6 chars | POST | `/login` | `email=admin@test.com` `password=12345` | Redirection 302, erreur de validation |
| 1.1.9 | CSRF token manquant | POST | `/login` | (sans token CSRF) | Réponse 403 |

### 1.2 Logout
| # | Test | Méthode | URL | Résultat attendu |
|---|------|---------|-----|------------------|
| 1.2.1 | Déconnexion | GET | `/logout` | Session détruite, redirection → `/login`, flashdata success |
| 1.2.2 | Accès page après logout | GET | `/employee/dashboard` | Redirection → `/login` |

---

## 2. SÉCURITÉ DES ROUTES

| # | Test | Méthode | URL | Rôle | Résultat attendu |
|---|------|---------|-----|------|------------------|
| 2.1 | Accès admin sans auth | GET | `/admin` | Aucun | Redirection → `/login` |
| 2.2 | Accès admin avec rôle employé | — | `/admin` | employe | Redirection → `/login` (403) |
| 2.3 | Accès RH avec rôle employé | — | `/rh/demandes` | employe | Redirection → `/login` (403) |
| 2.4 | Accès employee sans auth | GET | `/employee/dashboard` | Aucun | Redirection → `/login` |
| 2.5 | Accès employee avec rôle admin | — | `/employee/dashboard` | admin | Accès autorisé ✓ |
| 2.6 | Accès rh avec rôle admin | — | `/rh/demandes` | admin | Accès autorisé ✓ |
| 2.7 | Route inexistante | GET | `/xyz` | — | Erreur 404 |

---

## 3. CRUD ADMIN — EMPLOYÉS

| # | Test | Méthode | URL | Résultat attendu |
|---|------|---------|-----|------------------|
| 3.1 | Liste employés | GET | `/admin/employes` | Table affichée, 5+ employés |
| 3.2 | Formulaire création | GET | `/admin/employes/create` | Formulaire avec nom, prénom, email, rôle, département, actif |
| 3.3 | Création valide | POST | `/admin/employes/store` | Données valides → redirection, flashdata success |
| 3.4 | Création email déjà existant | POST | `/admin/employes/store` | `email=admin@techmada.mg` → erreur is_unique |
| 3.5 | Création données invalides | POST | `/admin/employes/store` | Nom vide → erreur de validation |
| 3.6 | Formulaire modification | GET | `/admin/employes/1/edit` | Pré-rempli avec données existantes |
| 3.7 | Modification valide | POST | `/admin/employes/1/update` | Flashdata success |
| 3.8 | Confirmation suppression | GET | `/admin/employes/1/confirm-delete` | Page de confirmation |
| 3.9 | Suppression valide | POST | `/admin/employes/5/delete` | Flashdata success, employé supprimé |
| 3.10 | Employé introuvable (edit) | GET | `/admin/employes/99999/edit` | Flashdata error |

---

## 4. CRUD ADMIN — DÉPARTEMENTS

| # | Test | Méthode | URL | Résultat attendu |
|---|------|---------|-----|------------------|
| 4.1 | Liste départements | GET | `/admin/departements` | Table affichée |
| 4.2 | Formulaire création | GET | `/admin/departements/create` | Formulaire nom + description |
| 4.3 | Création valide | POST | `/admin/departements/store` | Flashdata success |
| 4.4 | Création doublon | POST | `/admin/departements/store` | `nom=IT` → erreur is_unique |
| 4.5 | Modification valide | POST | `/admin/departements/1/update` | Flashdata success |
| 4.6 | Suppression avec employés | POST | `/admin/departements/1/delete` | Employés réaffectés à NULL, département supprimé |

---

## 5. CRUD ADMIN — TYPES DE CONGÉ

| # | Test | Méthode | URL | Résultat attendu |
|---|------|---------|-----|------------------|
| 5.1 | Liste types | GET | `/admin/types-conge` | Table affichée |
| 5.2 | Formulaire création | GET | `/admin/types-conge/create` | Formulaire libellé + deductible |
| 5.3 | Création valide | POST | `/admin/types-conge/store` | Flashdata success |
| 5.4 | Doublon libellé | POST | `/admin/types-conge/store` | `libelle=annuel` → erreur is_unique |
| 5.5 | Modification valide | POST | `/admin/types-conge/1/update` | Flashdata success |
| 5.6 | Suppression | POST | `/admin/types-conge/4/delete` | Flashdata success |

---

## 6. CRUD ADMIN — SOLDES

| # | Test | Méthode | URL | Résultat attendu |
|---|------|---------|-----|------------------|
| 6.1 | Liste soldes | GET | `/admin/soldes` | Table avec employé, type, année, jours, solde restant |
| 6.2 | Formulaire initialisation | GET | `/admin/soldes/create` | Sélecteurs employé + type + année + jours_attribues |
| 6.3 | Initialisation valide | POST | `/admin/soldes/store` | Flashdata success, ligne créée |
| 6.4 | Doublon employé/type/année | POST | `/admin/soldes/store` | Même combinaison → message d'erreur |
| 6.5 | Modification valide | POST | `/admin/soldes/1/update` | Flashdata success |
| 6.6 | Suppression | POST | `/admin/soldes/5/delete` | Flashdata success |

---

## 7. ESPACE EMPLOYÉ — DEMANDES

| # | Test | Méthode | URL | Résultat attendu |
|---|------|---------|-----|------------------|
| 7.1 | Dashboard employé | GET | `/employee/dashboard` | Stats + 5 dernières demandes |
| 7.2 | Formulaire nouvelle demande | GET | `/employee/demande/create` | Formulaire type, dates, motif |
| 7.3 | Soumission valide (non déductible) | POST | `/employee/demande/store` | Demande créée, statut `en_attente`, pas de déduction solde |
| 7.4 | Soumission dates invalides | POST | `/employee/demande/store` | `date_fin < date_debut` → erreur validation |
| 7.5 | Chevauchement bloqué | POST | `/employee/demande/store` | Dates chevauchant une demande existante → erreur |
| 7.6 | Solde insuffisant | POST | `/employee/demande/store` | Demande déductible > solde restant → message d'erreur détaillé |
| 7.7 | Soumission valide (déductible) | POST | `/employee/demande/store` | Demande créée, statut `en_attente`, solde intact |
| 7.8 | Mes demandes | GET | `/employee/mes-demandes` | Liste filtrable |
| 7.9 | Filtre par statut | GET | `/employee/mes-demandes?filtre=en_attente` | Uniquement en attente |
| 7.10 | Profil employé | GET | `/employee/profil` | Infos + stats demandes |

---

## 8. ESPACE RH — APPROBATION / REFUS

| # | Test | Méthode | URL | Résultat attendu |
|---|------|---------|-----|------------------|
| 8.1 | Demandes en attente | GET | `/rh/demandes` | Liste filtrée `en_attente` par défaut |
| 8.2 | Approuver une demande déductible | POST | `/rh/approuver/1` | Statut → `approuve`, `jours_pris` incrémenté (transaction) |
| 8.3 | Approuver une demande non déductible | POST | `/rh/approuver/2` | Statut → `approuve`, solde **inchangé** |
| 8.4 | Approuver avec commentaire | POST | `/rh/approuver/1` | `commentaire_rh` sauvegardé |
| 8.5 | Refuser une demande en attente | POST | `/rh/refuser/3` | Statut → `refuse`, **pas de déduction**, commentaire optionnel |
| 8.6 | Refuser une demande déjà approuvée | POST | `/rh/refuser/4` | Erreur flash (statut `approuve`) |
| 8.7 | Approuver une demande déjà approuvée | POST | `/rh/approuver/4` | Erreur flash (statut `approuve`) |
| 8.8 | Approuver sans solde suffisant | POST | `/rh/approuver/5` | Transaction rollback, message erreur solde |
| 8.9 | Filtres avancés | GET | `/rh/filtres?statut=approuve&type=1&search=hery` | Résultats filtrés correctement |
| 8.10 | Filtres par date | GET | `/rh/filtres?debut=2026-01-01&fin=2026-12-31` | Résultats dans la plage |

---

## 9. ESPACE RH — SOLDES ÉQUIPE

| # | Test | Méthode | URL | Résultat attendu |
|---|------|---------|-----|------------------|
| 9.1 | Liste soldes équipe | GET | `/rh/soldes-equipe` | Tous les employés avec leurs soldes |
| 9.2 | Filtre par département | GET | `/rh/soldes-equipe?departement_id=1` | Uniquement département sélectionné |
| 9.3 | Alerte solde faible | — | — | Badge warning si solde < 2 jours |
| 9.4 | Alerte solde négatif | — | — | Badge danger si solde < 0 |

---

## 10. ESPACE ADMIN — STATISTIQUES

| # | Test | Méthode | URL | Résultat attendu |
|---|------|---------|-----|------------------|
| 10.1 | Dashboard admin | GET | `/admin` | 4 cartes stats (employés, départements, congés, soldes) |
| 10.2 | Congés récents | GET | `/admin` | Tableau 5 dernières demandes |
| 10.3 | Congés en attente | GET | `/admin` | Tableau demandes en attente |
| 10.4 | Employés par département | GET | `/admin` | Graphique/progress bars |
| 10.5 | Soldes faibles | GET | `/admin` | Alertes < 2 jours restants |

---

## 11. ANNULATION ET RECRÉDIT DE SOLDE

| # | Test | Méthode | URL | Résultat attendu |
|---|------|---------|-----|------------------|
| 11.1 | Annuler demande en attente | GET `/employee/demande/1/annuler` + POST | | Statut → `annule`, **pas de recrédit** |
| 11.2 | Annuler demande approuvée (non déductible) | GET `/employee/demande/2/annuler` + POST | | Statut → `annule`, **pas de recrédit** |
| 11.3 | Annuler demande approuvée (déductible) | GET `/employee/demande/3/annuler` + POST | | Statut → `annule`, `jours_pris` diminué (recrédit) |
| 11.4 | Annuler demande annulée | GET `/employee/demande/6/annuler` | — | Erreur flash (déjà annulée) |
| 11.5 | Annuler demande refusée | GET `/employee/demande/7/annuler` | — | Erreur flash (refusée) |
| 11.6 | Confirmation avant suppression | GET puis POST | — | Page de confirmation intermédiaire |

---

## 12. CALCUL DES JOURS

| # | Test | Scénario | Résultat attendu |
|---|------|----------|------------------|
| 12.1 | Jours ouvrables (Lun-Ven) | 2026-01-05 → 2026-01-09 | 5 jours |
| 12.2 | Exclut week-end | 2026-01-09 (ven) → 2026-01-12 (lun) | 2 jours |
| 12.3 | Jours calendaires | 2026-01-09 → 2026-01-12 | 4 jours |
| 12.4 | Une seule journée | 2026-01-05 → 2026-01-05 | 1 jour |

---

## 13. VÉRIFICATION DES SOLDES

| # | Test | Scénario | Résultat attendu |
|---|------|----------|------------------|
| 13.1 | Solde restant = attribués - pris | 30 attribués, 5 pris | `calculerSoldeRestant()` = 25 |
| 13.2 | Insuffisant message complet | 5 restants, 8 demandés | Message détaillé avec manque |
| 13.3 | Pas de solde initialisé | Aucune ligne soldes | `verifierSoldeAvantApprobation` → insuffisant |
| 13.4 | Après approbation, solde déduit | 30 attribués, 5 pris, approuvé 3 jours | `jours_pris` = 8 |
| 13.5 | Après annulation, solde recrédité | 30 attribués, 8 pris, annulé 3 jours (approuvé) | `jours_pris` = 5 |
| 13.6 | Solde négatif impossible | `jours_pris` ne peut pas dépasser `jours_attribues` | Transaction rollback |

---

## 14. SÉCURITÉ GÉNÉRALE

| # | Test | Vérification |
|---|------|-------------|
| 14.1 | CSRF activé sur tous les formulaires POST | Token présent dans chaque formulaire |
| 14.2 | Injection SQL | Les requêtes utilisent Query Builder (pas de raw SQL) |
| 14.3 | XSS | `esc()` utilisé dans toutes les vues |
| 14.4 | Accès direct aux contrôleurs | `EmployeeBaseController` et `RHBaseController` bloquent sans session |
| 14.5 | Élévation de privilèges | Un employé ne peut pas accéder à `/rh/*` ou `/admin/*` |
| 14.6 | IDOR (annulation) | Un employé ne peut annuler que ses propres demandes (`$conge['employe_id'] === $userId`) |
| 14.7 | Mots de passe | Stockés avec `password_hash()` (bcrypt) |
| 14.8 | HTTPS | Le filtre `forcehttps` est activé en production |

---

> **Total : 50+ cas de test** couvrant l'ensemble des fonctionnalités critiques du système.