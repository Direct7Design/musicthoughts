psql -q -X -U musicthoughts -d musicthoughts_test -f schema-drop.pgsql 2>/dev/null
psql -q -X -U musicthoughts -d musicthoughts_test -f schema.pgsql 2>/dev/null
psql -q -X -U musicthoughts -d musicthoughts_test -f fixtures.pgsql
