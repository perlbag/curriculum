# CV en ligne

Le code source de [cv.glorian.ovh](https://cv.glorian.ovh) — mon CV, sous forme d'application Symfony.

Un CV de développeur affirme des choses qu'un document PDF ne peut pas prouver. Ce dépôt sert à ça : montrer comment je construis, sur un projet volontairement petit, plutôt que seulement le dire.

## La pile, et pourquoi

- **Symfony 7.3 / PHP 8.4** — le socle que je pratique au quotidien. Le projet reste au plus près du skeleton : pas de bundle superflu pour un site de cette taille.
- **FrankenPHP (mode worker) + Caddy** — un seul service à opérer, HTTPS automatique, et l'occasion de pratiquer le runtime qui remplace progressivement PHP-FPM dans l'écosystème.
- **AssetMapper + Tailwind (binaire standalone)** — aucune dépendance à Node ni chaîne de build JavaScript : les imports passent par l'importmap natif du navigateur, Tailwind est compilé par son binaire autonome. Moins de pièces mobiles à maintenir.
- **Twig rendu serveur, pas de framework front** — un CV est un document ; le HTML rendu côté serveur suffit, et la page reste lisible par n'importe quel outil.
- **Formulaire de contact** — validation Symfony, dont une contrainte téléphone maison appuyée sur libphonenumber, envoi via Mailjet.

Quelques attentions d'usage : mode sombre suivant la préférence système, styles d'impression (imprimer la page produit directement le CV papier, lien vers ce dépôt compris), métadonnées Open Graph pour le partage.

## Déploiement

Chaque push sur `main` déclenche le pipeline ([deploy.yml](.github/workflows/deploy.yml)) :

1. GitHub Actions construit l'image Docker (stage `frankenphp_prod`, cache de build GHA) ;
2. l'image est poussée sur GHCR, taguée par SHA de commit ;
3. un rolling update Docker Swarm (`docker service update`) bascule le service sur la nouvelle image, un endpoint de santé servant de sonde.

L'image déployée est identifiée par le commit exact qui l'a produite : revenir en arrière consiste à redéployer un tag précédent.

## IA et méthode de travail

Ce dépôt me sert aussi de terrain d'expérimentation pour le développement assisté par IA (Claude Code, Cursor) : l'historique git montre les commits co-écrits. Rien n'est intégré sans relecture — la responsabilité du code reste la mienne, pas celle de l'outil. Ces pratiques évoluent vite ; ce qui est décrit ici reflète un état, pas une doctrine.

## Lancer en local

```bash
docker compose build --pull --no-cache
docker compose up --wait
```

Puis ouvrir <https://localhost> (certificat TLS auto-généré à accepter).

## Origine

L'environnement Docker est basé sur [symfony-docker](https://github.com/dunglas/symfony-docker) de Kévin Dunglas ; sa documentation est conservée dans [docs/](docs/). Le reste — application, templates, pipeline de déploiement — est propre à ce projet.

Code sous licence [MIT](LICENSE).
