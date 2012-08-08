<?php
/**
 * Exception causes by a mistake of the user and that showd human readable message.
 * Those exceptions are catched by the main controller that an HTML view with the message is shown to the user.
 */
class UserError extends MyMedException {}
?>