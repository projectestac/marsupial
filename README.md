Marsupial
=========

“Marsupial” is a set of protocols defined by the Ministry of Education of Catalonia in the context of the 1-to-1 program “educat1x1/eduCAT 2.0”.

The target of “Marsupial” is to facilitate the communication between Virtual Learning Environments (VLE) and on-line commercial textbooks provided by publishers.

The “Marsupial” protocol defines three main functions:

1. An internal structure of digital textbooks, usually as a tree of chapters or topics, thus facilitating direct references from the VLE to specific contents of the book.
2. Management of credentials and authentication of students on textbook platforms, so after a single login into the VLE students can work with their digital textbooks (even from different publishers) without having to log-in again into each platform.
3. Reporting to the VLE results of exercises and activities made by students in digital textbooks. This allows teachers to have the results of all exercises collected in a single place, thus facilitating a global assessment.

“Marsupial” is based on SCORM and SOAP, and operates in two formats:

- Remote SCORM (like a regular SCORM object, but with media content residing outside the VLE and subjected to authentication. Results of exercises are reported directly to the VLE from the user's browser)
- Remote content (similar to Remote SCORM, but using web services to report results of exercises between textbook platforms and the VLE)

“Marsupial” has three main components:

- The documents containing the open specification
- The implementation of a “Marsupial” client for Moodle
- A minimalistic implementation of a publisher platform called Marsupial Publisher Simulator (MPS). It implements all the server-side protocols and provides “example” books for testing.

The “Marsupial” implementations are open source licensed under GPL v2
