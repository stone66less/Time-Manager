timemanager.info

How it all Started
------------------

This thin client application was built as a "proof of concept"
exercise using PHP. What was discovered was used in a much larger
development project.
Some six months later we returned to this application to see how
easy or difficult it would be to internationalize it,
keeping all text on the data base.
The error messages were already held in a data base table but a way
had to be found to hold label captions, page titles, etc. in more
than one language.
A user could be assigned a language and after logging-in, everything
would be displayed in that language.
Thanks to Google's translation service, an entire text file can be
translated into another language and the data loaded back into the
data base. This tar ball allows you to use either English, French or
German out-of-the-box.

Design Concepts Used
--------------------

It was imperative that it must follow the MVC paradigm.
We'd had prior experience with Apache Struts and so class files,
getters and setters, try ... catch blocks, etc., were not an alien
concept.
However, we didn't want a repeat of the "xml madness" as Struts
version one came to be known.
Still needed "glue" to hold everything together, so a single
controller (index.php) was developed, which also had the benefit
of tracking usage, as all requests are funneled via this controller.
Non use of the browser's "back" button was also disallowed as using it
defeats the purpose of having the controller do all the work. 
Another consideration was to have ALL error messages displayed
at once at the top of the page a la the Struts way.
The PHP code was written without using complex notation. I.e., the "KISS"
principle was invoked.


Development Environment
-----------------------

Time Manager Mark II was developed on a Debian 64 bit system using
PostgreSql 9.1, Apache 2.2, PHP 5, Iceweasel (Firefox)) and
ExecuteQuery 3.5. The code was cut using Bluefish.


Installation
------------

There are readme notes under a directory named "installation".
Please READ them carefully.
It is assumed that you have already used the above software and have
it installed. The installation readme does not contain instructions
for downloading and setting-up this software. If help is needed, please
refer to the respective sites for information.


Feedback
--------

We are interested to hear of any problems, glitches, suggestions for
improvements, etc.
Please send them to floriparob@gmail.com with "Time Manager" in the
subject field so they can be filtered into a separate in-box.
Have fun!