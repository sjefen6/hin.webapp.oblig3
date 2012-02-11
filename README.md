# Oblig 3 i faget Databaser og webapplikasjoner
Kode: Vegard Langås
Oppgave formidlet av Knut Collin

All kode er lisensiert BSD Modefied, se LICENSE.md

#Oppgavetekst
Du skal i denne oppgaven starte utviklingen av en personlig blogg. Bloggen skal videreutvukles i neste innlevering slik at du ved kursets slutt skal ha en noenlunde komplett egenutviklet blogg. Løsningen skal baseres på bruk av XHTML/HTML, stilark, JavaScript, Ajax, XML og PHP. Bloggen skal etterhvert benytte en database for lagring av informasjon, men i denne første innleveringen skal bloggen lagres på XML format i filsystemet. Bloggen er en del av et webområde der du presenterer deg selv.

## Oppgave:

1. Lag en startside for presentasjon av deg selv, du velger selv om denne er statisk eller om det er PHP som genererer hele eller deler av denne, siden skal inneholde litt informasjon om deg selv og et bilde.
1. Lag et web basert blog med en hovedside som viser alle, evt. et utvalg av alle, innlegg sortert på dato. Innlegg bør også kunne sorteres basert på stikkord (tags).
1. Eieren av bloggen skal måtte logge seg på for å legge inn nye innlegg.
1. Anonyme brukere skal kunne lese alle innlegg
1. Bloggen lagres i filsystemet i XML format, benytt PHP og SimpleXML, skriv gjerne en egen dataksess klasse der du benytter SimpleXML i denne oppgaven, klassen kan senere utvides for å støtte lagring i database
1. Smarty templates benyttes for å skille HTML og PHP kode
1. Benytt objektorienterte prinsipper ved PHP kodingen.
1. Du bestemmer selv formatet for et blogg innlegg i XML, hvilke elementer som skal være med. Naturlige elementer kan være Tittel, tekst for innlegget, dato, forfatter, stikkord (tags)
1. Alle dokumentene skal benytte det samme stilarket slik at stil/utseende blir ensartet. Tabeller skal ikke benyttess for layout, JavaScript og AJAX benyttes etter behov
1. Valider dokumenter og stilark hos http://www.w3c.org/.

## HTML5 & CSS3

Løsningen bør bruke noe av nye elementer & egenskaper til HTML5 og CSS3, du velger selv, men sjekk dette ut i de vanligste nettleserne, dersom noen av den funksjonaliteten du benytter kun virker i en spesifikk nettleser så må du opplyse om dette.

Generelle krav til løsningen.
* Bruk bare relative URL'er i lenkene, og legg alle filer i samme mappe. Dette betyr at det kun blir filnavn i lenkene. Dette gjør at webområdet med alle filene er lett flyttbar. Eventuelle bilder legges også i samme mappe, evt. en undermappe. Unngå norske tegn (æøå) og blanke tegn (mellomrom) i filnavn da dette ofte skaper problemer for ulike nettlesere.

* All input fra brukere må vaskes før lagring på XML format, benytt strip_tags() eller html_entities() for dette. Benytt utf8 metodene utf8_encode() og utf8_decode() ved skriving og lesing av XML filen i utf-8 format.

NB! All kode som benyttes skal være egenprodusert. I de tilfellene der det benyttes kode skrevet av andre skal dette opplyses. Kildehenvisninger er meget viktig.

## Publisering.
* Endelig løsning publiseres under eget område på kark.hin.no.

## Innlevering
All kildekode leveres elektronisk på It's learning som en ZIP/TAR fil eller liknende.. Løsningen må være kjørbar på webtjener, slik at det er lett for faglærer/sensor å vurdere løsningen. Det er derfor særdeles viktig at URL adresser og nødvendige brukernavn og passord er lett tilgjengelige. ZIP filen skal inneholde en fil med navn README.TXT der alle nødvendige URL adresser og brukernavn og passord ligger.