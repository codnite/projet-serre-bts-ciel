#!/bin/bash

mysql -u admin -padmin serre_db <<EOF
DELETE FROM humidite
WHERE horaire < NOW();

DELETE FROM temperature
WHERE horaire < NOW();

DELETE FROM humidite_sol
WHERE horaire < NOW();
EOF
