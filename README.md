Marsupial
=========

"Marsupial" is a set of protocols defined by the Ministry of Education of Catalonia in the context of the 1-to-1 program *educat1x1 - eduCAT 2.0*.

Marsupial has been also adopted as a main interoperability protocol in the *Punto Neutro de Recursos Educativos Digitales* platform, promoted by the Spanish Ministry of Education.

The target of Marsupial is to facilitate the communication between Virtual Learning Environments (VLE) and on-line commercial textbooks provided by publishers.


##Main functions

Marsupial has three main functions:

1. To define an internal structure of digital textbooks, usually as a tree of chapters or topics, thus facilitating direct references from the VLE to specific contents of the book.
2. To manage credentials and authentication of students on textbook's platforms, so after a single login into the VLE, students can work with their digital textbooks (even from different publishers) without having to log-in again into each platform.
3. To report into the VLE the results of exercises and activities made by students in digital textbooks. This allows teachers to have the results of all exercises collected in a single place, thus facilitating a global assessment.

##Formats

Marsupial is based on SCORM and SOAP, and operates in two formats:

- **Remote SCORM**: Like a regular SCORM object, but with media content residing outside the VLE and subjected to authentication. Results of exercises are reported directly to the VLE from the user's browser.
- **Remote content**: Similar to Remote SCORM, but using web services to report results of exercises between textbook platforms and the VLE.

##Components
Marsupial has three main components:

|Folder|Component|
|:------|:---------|
|/docs|The documents containing the open specification|
|/moodle|The implementation of a “Marsupial” client for Moodle|
|/mps|A minimalistic implementation of a publisher platform, called Marsupial Publisher Simulator (MPS). It implements all the server-side protocols and provides “example” books for testing|

##Licensing
Marsupial is open source software, licensed under the terms of the [GNU General Public License v2](http://www.gnu.org/licenses/gpl-2.0.html).

##RGrade
[RGrade](https://github.com/imartel/Rgrade) is a very useful companion of Marsupial, freely available on: https://github.com/imartel/Rgrade. It's a different project, not included by default in Marsupial.

RGrade was created by [Text-La Galera](http://www.text-lagalera.cat/) to simplify and make more usable the Moodle Gradebook when dealing with digital textbooks in *Remote Content*.
