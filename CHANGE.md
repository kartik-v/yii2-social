version 1.3.0
=============
**Date:** 15-Jan-2015

- (enh #6): Remove Facebook PHP SDK support (old version v3.0). 
- (enh #10): German translations added
- (enh #11): Hungarian translations added
- (enh #12): Ukranian & Russian translations added
- (enh #13): New features for PHP SDK v4.0
    - Implement an extended `FacebookRedirectLoginHelperX` class for handling sessions the Yii way
    - New `getFbLoginHelper` method in social module to fetch the Redirect Login Helper object instance based on the module level facebook settings
    - New `initFbSession` method in social module to initialize a facebook session
    - New `getFbSession` method in social module to get a facebook session either based on string token, redirect login url, canvas login, or Javascript login.
    - New `getFbGraphUser` method gets the Facebook graph user object for current user.
    - Changes from earlier release
        - Method `getFbApi`has been removed from the social module
        - Module `getFbUser` has been removed from the social module
        - Set release to stable.

version 1.2.0
=============
**Date:** 10-Nov-2014

- Set release to stable

version 1.1.0
=============
**Date:** 01-Jul-2014

- PSR4 alias change
- (enh #8): Allow language configuration for Facebook, Twitter, and Google plugins.

version 1.0.0
=============
**Date:** 01-Dec-2013

- Initial release
