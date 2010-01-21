createdb -O musicthoughts -E UTF8 musicthoughts
psql -X -U musicthoughts -d musicthoughts -f schema.pgsql 

createdb -O musicthoughts -E UTF8 musicthoughts_test
psql -X -U musicthoughts -d musicthoughts_test -f schema.pgsql 
psql -X -U musicthoughts -d musicthoughts_test -f fixtures.pgsql 
