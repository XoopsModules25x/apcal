Module Name : APCal
Version Number : 2.2.1
Module developer : Antiques Promotion <http://xoops.antiquespromotion.ca>

MODULE DESCRIPTION
APCal is a powerful calendar module for Xoops.

There is no update since 2006 on the piCal module and the developer doesn't seem to work on it,
so we decided to upgrade it.

NEW FEATURES:
- Captcha added in the "tell a friend" form for anonymous users to prevent spammers to use it.
- Option : Enable/Disable the "tell a firend" form.
- Pictures and Main category added in the comming soon block (with enable/disable option) 

ORIGINAL FEATURES:
- User Registration (Thanks to Goffy)
  - Users can subcribe to events and receive notifications.
  - Manage wich events can take subscriptions.
  - Option: Turn it on and off.
  - Option: Set the maximum number of subscribers.
  - Option: Set the deadline to subscribe.
- Pictures
  - Thumbnail pictures with viewer in event view.
  - Option : Add pictures to an event including a separated main picture.
  - Option : Main picture display in Monthly View (on mouse over), List View, Daily View and Weekly View.
- Monthly view
  - Monthly view with linear view for one event. (instead of repeating over and over)
  - Each category have its own color.
  - Legend of categories with categories colors.
  - Option : bigger columns for chosen days.
- Display
  - Seperate field for website and e-mail.
  - More neutral colors for theme images, should be a better fit with most website.
  - Reorganisation of admin preferences.
  - Improved customizable theme.
- Sharing
  - Tell a friend feature.
  - New sharing buttons (LindedIn and Delicious).
  - Option : display the share this calendar link.
  - Option : API that allows webmasters to show easily your calendar on their website (linking back to your site).
- Map
  - Markers with number representing the date of event with next event first.
  - Option : Enable/disable map showing all events location.
  - Option : Individual map showing event location.
- SEO
  - Optimised for Google (Title and Description).
  - Option : URL Rewrite if allowed by server.
  - Option : Add a description in HTML for each categories.
  - Option : Display title of categories in H1.
- Events
  - Empty fields doesn't display in the event view.
  - Option : Choose which category will be used for an event (as we keep multi-category feature). 
  - Option : Choose different hours for the same event (if multiple days are selected).
  - Option : Navigation menu to see previous and next event.

- Option : Perpetual holidays for USA (En), Canada (En), Quebec (Fr), France (Fr).


ORIGINAL PICAL FEATURES
- 4 Different views (Daily, Weekly, Monthly, Yearly).
- Users can add events if permission is granted.
- Export to ICS format.

LICENSE
This module is released under the GPL license. See LICENSE.txt for details. 

LANGUAGES
up to v2.1.1:  English, French, German
up to v2.0.4:  English, French, German, Spanish
before v1.0.0: English, French, German, Spanish, Japanese, Dutch, Russian, Tchinese, Swedish, Portuguese, BrasilPortuguese

CREDITS
This module is based on piCal, originally developed by GIJ=CHECKMATE <gij@peak.ne.jp>.
Sources created by Antiques Promotion, Goffy, GIJ=CHECKMATE, Ryuji.
Templates created by Antiques Promotion, GIJ=CHECKMATE.
Documentation by Antiques Promotion.
Testing by Antiques Promotion.
Translation by:
    Antiques Promotion    English           (up to v2.1.1)
    Antiques Promotion    French            (up to v2.1.1)
    Goffy                 German            (up to v2.1.1)   
    marpe                 Spanish           (up to v2.0.4)
    Chimpel               Nederlands        (before v1.0.0)
    FrenchmaN             Russian           (before v1.0.0)
    Xoobs                 TChinese          (before v1.0.0)
    Leif Madsen           Swedish           (before v1.0.0)
    Olivier               Portuguese        (before v1.0.0)
    Marcelo Yuji Himoro   Brasil Portuguese (before v1.0.0)
    Cubiq                 Italian           (before v1.0.0)   
    kurak_bu              Polish            (before v1.0.0)

REFERENCES
phpicalendar    http://phpicalendar.sourceforge.net
PiCal           http://xoops.peak.ne.jp

FEEDBACK
For any suggestions, comments, bug reports and feature requests visit the development site at: http://xoops.antiquespromotion.ca

FAQ
Q) The Displayed time is different from the time input time
A) This is caused the wrong setting of Time Zones in your XOOPS. Check Time Zones of your account, default account, or server.