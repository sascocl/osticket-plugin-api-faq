osTicket Plugin API FAQ
=======================

Plugin for query the FAQ via API

You can make a search in:

    https://osticket.example.com/api/http.php/faq/search.json?q=query+string

The API return an JSON with the fields:

- category
- faq_id
- question
- keywords
- url

The search is made only in public FAQ.

Before making queries, you must create a FULL TEXT INDEX:

    CREATE FULLTEXT INDEX ost_faq_fulltext_idx  ON ost_faq (question, answer, keywords);

License of the project: AGPL
