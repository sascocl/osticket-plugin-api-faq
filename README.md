osTicket Plugin API FAQ
=======================

Plugin for query the FAQ via API

You can make a search in:

    https://osticket.example.com/api/http.php/faq/search.json?q=query+string

The API return an array of JSON objects with the fields:

- category
- faq_id
- question
- keywords
- url

You can pass 2 variables via GET (in the URL):

- search_in_answer = 1 will search inside answer column (by default disabled)
- search_mode = natural will search with "IN NATURAL LANGUAGE MODE" of FULL TEXT SEARCH (by default: BOOLEAN MODE)

The search is made only in public FAQ and all the words are required for match.

Before making queries, you must create a FULL TEXT INDEX:

    CREATE FULLTEXT INDEX ost_faq_fulltext_idx  ON ost_faq (question, answer, keywords);
    CREATE FULLTEXT INDEX ost_faq_fulltext2_idx  ON ost_faq (question, keywords);

License of the project: AGPL
