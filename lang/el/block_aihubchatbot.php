<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle. If not, see <http://www.gnu.org/licenses/>.

/**
 * Greek language strings for the AIHub Chatbot block.
 *
 * @package   block_aihubchatbot
 * @copyright 2026 Human Asset
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'Βοηθός συνομιλίας AIHub';
$string['defaulttitle'] = 'Βοηθός συνομιλίας AIHub';
$string['aihubchatbot:addinstance'] = 'Προσθήκη μπλοκ βοηθού συνομιλίας AIHub';
$string['aihubchatbot:myaddinstance'] = 'Προσθήκη μπλοκ βοηθού συνομιλίας AIHub στο Ταμπλό';
$string['aihubchatbot:configure'] = 'Ρύθμιση μπλοκ βοηθού συνομιλίας AIHub';
$string['configtitle'] = 'Τίτλος μπλοκ';
$string['configembedcode'] = 'Κώδικας ενσωμάτωσης AIHub';
$string['configembedcode_help'] = 'Επικολλήστε τον κώδικα ενσωμάτωσης iframe ή την απλή διεύθυνση URL ενσωμάτωσης που δημιουργεί το AIHub. Η διεύθυνση προέλευσης πρέπει να χρησιμοποιεί HTTPS, να ανήκει στη ρυθμισμένη βασική διεύθυνση URL του AIHub και να χρησιμοποιεί τη διαδρομή /embed.';
$string['configdisplaymode'] = 'Τρόπος εμφάνισης';
$string['configdisplaymode_help'] = 'Η αιωρούμενη εμφάνιση χρησιμοποιεί κουμπί εκκίνησης και παράθυρο που παρέχονται από το Moodle, διατηρώντας το AIHub στο ίδιο iframe με υπογεγραμμένα δεδομένα. Η ενσωματωμένη εμφάνιση προβάλλει τον βοηθό συνομιλίας μέσα στο μπλοκ του Moodle.';
$string['displaymodeinline'] = 'Ενσωματωμένη';
$string['displaymodefloating'] = 'Αιωρούμενη';
$string['configheight'] = 'Ύψος iframe';
$string['configheight_help'] = 'Ύψος σε εικονοστοιχεία για την ενσωμάτωση iframe. Οι τιμές περιορίζονται μεταξύ 320 και 1200 εικονοστοιχείων.';
$string['contextnotice'] = 'Αυτό το μπλοκ αποστέλλει στο AIHub υπογεγραμμένα δεδομένα πλαισίου του Moodle, τα οποία περιλαμβάνουν μια ψευδωνυμοποιημένη τιμή κατακερματισμού χρήστη, το αναγνωριστικό και το σύντομο όνομα του μαθήματος, το αναγνωριστικό του στιγμιοτύπου μπλοκ, τη διεύθυνση URL του ιστοτόπου Moodle, τη χρονική σήμανση και την υπογραφή.';
$string['settings:aihubbaseurl'] = 'Βασική διεύθυνση URL του AIHub';
$string['settings:aihubbaseurl_desc'] = 'Εμφανίζονται μόνο διευθύνσεις URL ενσωμάτωσης από αυτόν τον εξυπηρετητή. Χρησιμοποιήστε τη δημόσια διεύθυνση URL της εφαρμογής AIHub, για παράδειγμα https://aihub.humanasset.com.';
$string['settings:sharedsecret'] = 'Κοινό μυστικό κλειδί υπογραφής';
$string['settings:sharedsecret_desc'] = 'Υποχρεωτικό. Το μπλοκ χρησιμοποιεί αυτό το μυστικό κλειδί για να δημιουργήσει μια ψευδωνυμοποιημένη τιμή κατακερματισμού χρήστη και να υπογράψει τα δεδομένα πλαισίου του Moodle με HMAC-SHA256, ώστε το AIHub να μπορεί να επαληθεύσει ότι τα δεδομένα προέρχονται από αυτόν τον ιστότοπο Moodle.';
$string['settings:defaultheight'] = 'Προεπιλεγμένο ύψος iframe';
$string['settings:defaultheight_desc'] = 'Προεπιλεγμένο ύψος σε εικονοστοιχεία για την ενσωμάτωση iframe, όταν δεν έχει οριστεί διαφορετικό ύψος στο συγκεκριμένο μπλοκ.';
$string['iframetitle'] = 'Βοηθός συνομιλίας AIHub';
$string['invalidembedcode'] = 'Ο κώδικας ενσωμάτωσης AIHub δεν είναι έγκυρος. Επικολλήστε μια έγκυρη ετικέτα iframe ή διεύθυνση URL ενσωμάτωσης του AIHub.';
$string['untrustedembedcode'] = 'Η προέλευση της ενσωμάτωσης πρέπει να χρησιμοποιεί HTTPS, να συμφωνεί με τη ρυθμισμένη βασική διεύθυνση URL του AIHub και να χρησιμοποιεί τη διαδρομή /embed.';
$string['scriptembeddisabled'] = 'Η ενσωμάτωση μέσω script είναι απενεργοποιημένη για λόγους ασφαλείας. Επικολλήστε κώδικα ενσωμάτωσης iframe ή απλή διεύθυνση URL ενσωμάτωσης του AIHub.';
$string['missingembedcode'] = 'Αυτό το μπλοκ βοηθού συνομιλίας AIHub δεν έχει ρυθμιστεί ακόμη.';
$string['missingembedcodeconfigure'] = 'Ανοίξτε τις ρυθμίσεις του μπλοκ και επικολλήστε τον κώδικα ενσωμάτωσης AIHub για αυτό το μάθημα.';
$string['loginrequired'] = 'Συνδεθείτε για να χρησιμοποιήσετε αυτόν τον βοηθό συνομιλίας AIHub.';
$string['sharedsecretrequired'] = 'Ο βοηθός συνομιλίας AIHub δεν είναι διαθέσιμος μέχρι ένας διαχειριστής ιστοτόπου να ρυθμίσει το κοινό μυστικό κλειδί υπογραφής.';
$string['floatingopen'] = 'Άνοιγμα βοηθού τεχνητής νοημοσύνης';
$string['floatingclose'] = 'Κλείσιμο βοηθού τεχνητής νοημοσύνης';
$string['privacy:metadata:aihub'] = 'Το μπλοκ βοηθού συνομιλίας AIHub αποστέλλει δεδομένα πλαισίου του Moodle στο AIHub, ώστε ο βοηθός να μπορεί να αναγνωρίζει το μάθημα και τη συνεδρία του χρήστη.';
$string['privacy:metadata:aihub:moodle_user_hash'] = 'Μια ψευδωνυμοποιημένη τιμή κατακερματισμού HMAC-SHA256 που προκύπτει από το αναγνωριστικό χρήστη του Moodle και το κοινό μυστικό κλειδί του ιστοτόπου.';
$string['privacy:metadata:aihub:moodle_course_id'] = 'Το αναγνωριστικό του μαθήματος Moodle στο οποίο εμφανίζεται το μπλοκ.';
$string['privacy:metadata:aihub:moodle_course_shortname'] = 'Το σύντομο όνομα του μαθήματος Moodle στο οποίο εμφανίζεται το μπλοκ.';
$string['privacy:metadata:aihub:moodle_block_instance_id'] = 'Το αναγνωριστικό του στιγμιοτύπου μπλοκ στο Moodle.';
$string['privacy:metadata:aihub:moodle_site_url'] = 'Η διεύθυνση URL του ιστοτόπου Moodle.';
$string['privacy:metadata:aihub:moodle_context_timestamp'] = 'Η χρονική σήμανση κατά τη δημιουργία των δεδομένων πλαισίου του Moodle.';
$string['privacy:metadata:aihub:moodle_context_signature'] = 'Μια προαιρετική υπογραφή HMAC που δημιουργείται από τα δεδομένα πλαισίου του Moodle.';
$string['usethemecolor'] = 'Χρήση του κύριου χρώματος του θέματος Moodle';
$string['launchercolor'] = 'Χρώμα κουμπιού εκκίνησης';
$string['launchericon'] = 'Εικονίδιο κουμπιού εκκίνησης';
$string['launchericon_chat'] = 'Συννεφάκι συνομιλίας';
$string['launchericon_robot'] = 'Ρομπότ';
$string['launchericon_education'] = 'Καπέλο αποφοίτησης';
$string['launchericon_help'] = 'Ερωτηματικό';
$string['launchericon_idea'] = 'Λαμπτήρας';
$string['invalidlaunchercolor'] = 'Επιλέξτε ένα χρώμα ή εισαγάγετε έναν εξαψήφιο δεκαεξαδικό κωδικό χρώματος, όπως #155eef.';
$string['invalidlaunchericon'] = 'Επιλέξτε ένα από τα διαθέσιμα εικονίδια του κουμπιού εκκίνησης.';
$string['globalsettings'] = 'Γενικές ρυθμίσεις βοηθού συνομιλίας AIHub';
$string['invalidbaseurl'] = 'Εισαγάγετε μια έγκυρη διεύθυνση URL HTTPS του AIHub, χωρίς όνομα χρήστη ή κωδικό πρόσβασης.';
$string['invaliddefaultheight'] = 'Εισαγάγετε ύψος μεταξύ 320 και 1200 εικονοστοιχείων.';
$string['launchericon_image'] = 'Μεταφορτωμένη εικόνα';
$string['launcherimage'] = 'Εικόνα κουμπιού εκκίνησης';
$string['launcherimage_help'] = 'Μεταφορτώστε μία εικόνα PNG ή JPG (έως 5 MB, 4096 εικονοστοιχεία ανά πλευρά και 4 megapixel). Επιλέξτε «Μεταφορτωμένη εικόνα» για να τη χρησιμοποιήσετε. Η μικρότερη διάσταση προσαρμόζεται στα 52 εικονοστοιχεία και το τμήμα που περισσεύει περικόπτεται συμμετρικά γύρω από το κέντρο. Η τετράγωνη εικόνα που προκύπτει εμφανίζεται σε μέγεθος 26 επί 26 εικονοστοιχείων. Η διαφάνεια PNG διατηρείται. Για επαναφορά, αφαιρέστε το αρχείο και επιλέξτε ένα από τα ενσωματωμένα εικονίδια.';
$string['invalidlauncherimage'] = 'Μεταφορτώστε μια έγκυρη εικόνα PNG ή JPG εντός των ορίων μεγέθους αρχείου και διαστάσεων.';
$string['launcherimagerequired'] = 'Μεταφορτώστε μια εικόνα ή επιλέξτε ένα από τα ενσωματωμένα εικονίδια.';
$string['launchergdrequired'] = 'Για την επεξεργασία εικόνων του κουμπιού εκκίνησης απαιτείται η επέκταση GD της PHP με υποστήριξη PNG και JPEG.';
$string['privacy:metadata:launcherfiles'] = 'Το Moodle αποθηκεύει μια επεξεργασμένη εικόνα του κουμπιού εκκίνησης ως κοινόχρηστη ρύθμιση του μπλοκ. Στην περιοχή αρχείων του μπλοκ διατηρείται μόνο το τετράγωνο PNG, το οποίο δεν συνδέεται με μεμονωμένο εκπαιδευόμενο.';
$string['showlauncherlabel'] = 'Εμφάνιση ετικέτας δίπλα στο κουμπί εκκίνησης';
$string['launcherlabel'] = 'Ετικέτα κουμπιού εκκίνησης';
$string['defaultlauncherlabel'] = 'Ρωτήστε τον βοηθό του μαθήματος';
$string['autoopenonce'] = 'Αυτόματο άνοιγμα μία φορά ανά μάθημα';
$string['autoopenonce_help'] = 'Άνοιγμα μία φορά για κάθε χρήστη και μάθημα σε αυτό το πρόγραμμα περιήγησης. Το κλείσιμο ή το χειροκίνητο άνοιγμα του βοηθού καταγράφει την επίσκεψη. Η εκκαθάριση του χώρου αποθήκευσης του προγράμματος περιήγησης ή η αλλαγή του κλειδιού υπογραφής επαναφέρει αυτήν την επιλογή. Το αυτόματο άνοιγμα απενεργοποιείται στη λειτουργία επεξεργασίας ή όταν ο χώρος αποθήκευσης του προγράμματος περιήγησης δεν είναι διαθέσιμος.';
$string['allowdragresize'] = 'Να επιτρέπονται η μετακίνηση και η αλλαγή μεγέθους σε υπολογιστή';
$string['allowexpand'] = 'Να επιτρέπονται η μεγέθυνση και η επαναφορά';
$string['windowminimize'] = 'Ελαχιστοποίηση';
$string['windowexpand'] = 'Μεγέθυνση';
$string['windowrestore'] = 'Επαναφορά';
$string['windowreset'] = 'Επαναφορά θέσης και μεγέθους';
$string['windowmove'] = 'Μετακίνηση παραθύρου βοηθού: σύρετε ή χρησιμοποιήστε τα πλήκτρα βέλους';
$string['windowresize'] = 'Αλλαγή μεγέθους παραθύρου βοηθού: σύρετε ή χρησιμοποιήστε τα πλήκτρα βέλους';
$string['configtitle_help'] = 'Αφήστε το πεδίο κενό για να χρησιμοποιείται ο μεταφρασμένος προεπιλεγμένος τίτλος του μπλοκ στην τρέχουσα γλώσσα του Moodle. Ένας προσαρμοσμένος τίτλος αντικαθιστά την προεπιλογή. Διαγράψτε έναν ήδη αποθηκευμένο τίτλο για να επαναφέρετε την αυτόματη μετάφραση.';
$string['launcherlabel_help'] = 'Αφήστε το πεδίο κενό για να χρησιμοποιείται η μεταφρασμένη προεπιλεγμένη ετικέτα του κουμπιού εκκίνησης στην τρέχουσα γλώσσα του Moodle. Μια προσαρμοσμένη ετικέτα αντικαθιστά την προεπιλογή. Διαγράψτε μια ήδη αποθηκευμένη ετικέτα για να επαναφέρετε την αυτόματη μετάφραση.';
