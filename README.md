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

License of the project: AGPL
