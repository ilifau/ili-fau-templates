

Landing Pages
=============

Version 0.1.2

***Das Plugin befindet sich derzeit In Entwicklung.*** Die hier angezeigten Informationen sind vorläufig.

## Beschreibung

Das Plugin integriert ein Template für Landing Pages in den Webauftritt. Landing Pages bestehen aus einem Slider und sogenannten Themenboxen.

## Einstellungen

Einige grundlegende Einstellungen des Plugins lassen sich unter Einstellungen > ILI FAU Templates vornehmen. Dazu gehören

- die maximale Anzahl von Slides pro Seite
- die Länge des Anreißertextes der Themenboxen
- die URL zu einem Fallback-Bild, falls keine Slides ausgewählt wurden

Themenboxen
----------------

Das Plugin stellt einen neuen Beitragstyp "Themenbox" zur Verfügung. Themenboxen können im Kontext des Landing Page Templates aus einer Liste ausgewählt werden und werden dann unterhalb der Slideshow angezeigt.

### Shortcode

Für die Einbettung von Themenboxen in beliebige Beiträge und Seiten steht ein Shortcode zur Verfügung. Beispiel:

**[themenboxen ids="12,26,48" text_length="128" read_more="0" remove_skew="1"]**

- **ids** bezieht sich auf die Beitrags-IDs der anzuzeigenden Themenboxen
- **text_length** gibt die Länge der Anreißertexte in den Themenboxen vor
- **read_more** legt fest, ob der Link "Weiterlesen" im Anreißertext angezeigt wird
- **remove_skew** ermöglicht die Entfernung der "Schräge"
