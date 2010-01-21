# create NEW test fixtures 
dropdb musicthoughts_test
createdb -O musicthoughts -E UTF8 musicthoughts_test
psql -q -X -U musicthoughts -d musicthoughts_test -f schema.pgsql 
psql -q -X -U musicthoughts -d musicthoughts_test -f fixtures.pgsql 
