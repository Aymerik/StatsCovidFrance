# StatsCovidFrance

Publication automatique des statistiques Covid sur Twitter (cas, décès, hospitalisations, réanimations).

## Installation du projet
1. Télécharger le projet
2. Installer les dépendances avec composer install
3. Créer le fichier config.ini à la racine avec : 
```ini
CONSUMER_KEY=
CONSUMER_SECRET=
ACCESS_TOKEN=
ACCESS_TOKEN_SECRET=
```

## Utilisation

Commande : `php update-data.php`

Options :
* Option 1 : `--tweet` ou `--no-tweet` pour tweeter ou non le résultat (si les données sont disponibles). Par défaut : `--no-tweet`
* Option 2 : date au format `YYYY-mm-dd` pour avoir les données d'une date précise. Par défaut : Date du jour
