Wiselib-Online
==============
Wiselib-Online is a cloud service for editing wiselib code. It useses code from the existing codebender.cc project. Wiselib-Online will remove the need for the complex installation of wiselib developer tools, as users can simply fork wiselib and work on it using this web application.

Features of Wiselib-Online
--------------------------
The main features of this project will be -
<ol>
<li>User authentication using Wisebed/Github credentials</li>
<li>Github-Based Branch creation and push back changes</li>
<li>Collaborative editing</li>
<li>Templates and guides</li>
<li>Integration with Wisebed experimental facilities</li>
</ol>

Installation
------------
###Requirements
<ul>
<li>Apache2</li>
<li>PHP (5.3.3 or higher)</li>
<li>MySQL</li>
<li>MongoDB</li>
</ul>
###Setup Instructions
<ol>
<li>Clone this repository to obtain the application files:<br/>
<code>~$ git clone https://github.com/abhshkrv/Wiselib-Online</code></li>
<li>Create logs and cache dirs in Symfony/App directory and give write permissions</li>
<li>Create necessary database for the app to use.</li>
<li>In the Symfony/App/Config folder, copy the contents parameters.dist to a new file parameters.ini, and update it accordingly</li>
<li>If you have PHP 5.4, you could use the built-in web server. The built-in server should be used only for development purpose, but it can help you to start your project quickly and easily.
<br/>
<code>$ php -S localhost:80 -t /path/to/www</code></li>
</ol>
Contact
-------
Questions/Comments/Suggestions? Get in touch at <abhishekravi1992@gmail.com>
