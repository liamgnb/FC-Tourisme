## Mise en place de la BDD
```shell
# Création de la base de donnée :
symfony console d:d:c
```
```shell
# Éxécution des migrations
symfony console d:m:m 
```
```shell
# Importation des villes de franche comté
symfony console app:import-villes-franche-comte
```
```shell
# Éxécution des fixtures
symfony console d:f:l --purge-exclusions=ville
```