BEGIN;

COPY categories (id, description) FROM stdin;
1	composing music
2	writing lyrics
3	music listening
4	experiments
5	writers block
6	practicing
7	performing
8	recording
9	music business
10	career
11	big thoughts
12	technology
\.

INSERT INTO authors (id, name, url) VALUES (1, 'Willy Wonka', 'http://chocolate.com');

INSERT INTO contributors (id, shared_id, name, email, url, place) VALUES (1, 1, 'Derek Sivers', 'derek@sivers.org', 'http://sivers.org', 'Seattle, USA');

INSERT INTO thoughts (id, approved, author_id, contributor_id, created_at, as_rand, source_url) VALUES (1, 't', 1, 1, '2008-01-01', 't', 'http://chocolate.org');

INSERT INTO thought_translations (id, thought_id, lang, thought) VALUES (1, 1, 'en', 'Chocolate is divine.');
INSERT INTO thought_translations (id, thought_id, lang, thought) VALUES (2, 1, 'fr', 'Le chocolat est divine.');
INSERT INTO thought_translations (id, thought_id, lang, thought) VALUES (3, 1, 'ja', 'チョコレートの神である');

INSERT INTO categories_thoughts (thought_id, category_id) VALUES (1, 11);
INSERT INTO categories_thoughts (thought_id, category_id) VALUES (1, 5);

COMMIT;

BEGIN;
SELECT pg_catalog.setval('categories_id_seq', (SELECT MAX(id) FROM categories) + 1, false);
SELECT pg_catalog.setval('authors_id_seq', (SELECT MAX(id) FROM authors) + 1, false);
SELECT pg_catalog.setval('contributors_id_seq', (SELECT MAX(id) FROM contributors) + 1, false);
SELECT pg_catalog.setval('thoughts_id_seq', (SELECT MAX(id) FROM thoughts) + 1, false);
SELECT pg_catalog.setval('thought_translations_id_seq', (SELECT MAX(id) FROM thought_translations) + 1, false);
COMMIT;

