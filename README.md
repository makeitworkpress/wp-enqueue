# wp-enqueue
The WP Enqueue class provides a wrapper to make enqueueing scripts and styles in WordPress more easy.

## Usage
Include the WP Optimize class in your plugin, theme or child theme file. 

### Add your scripts and styles as one array
You can add scripts and styles in one array, following the syntax as advised by WordPress. The script automatically recognizes whether you are adding a stylesheet or css file. In addition, you can add additional parameters to alter the behaviour of your scripts and styles.

A basic example of an array of assets:

            $assets = array(
                array('handle' => 'some-js', 'src' => get_stylesheet_directory_uri() . '/test.js', array(), NULL, true)
                array('handle' => 'some-css', 'src' => get_stylesheet_directory_uri() . '/test.css', array(), NULL, 'all'),                
                array('handle' => 'some-css-front-and-admin', 'src' => get_stylesheet_directory_uri() . '/test.css', 'context' => 'both'),                
                array('handle' => 'some-admin-js', 'src' => get_stylesheet_directory_uri() . '/admin.js', 'context' => 'admin')
                array('handle' => 'some-login-css', 'src' => get_stylesheet_directory_uri() . '/login.css', 'context' => 'login')
                array('handle' => 'some-exluded-css', 'src' => get_stylesheet_directory_uri() . '/exclude.css', 'context' => 'admin', 'exclude' => array('edit.php'))
                array('handle' => 'some-included-css', 'src' => get_stylesheet_directory_uri() . '/include.css', 'context' => 'admin', 'include' => array('edit.php'))
                array('handle' => 'some-existing-css', 'action' => 'dequeue')
            );
            
All scripts and styles are enqueued with a priority of 20, so later as the default usage.

### Additional Properties
You can add additional properties in your array which extend the functionality of enqueueing.

**action (string)**
Allows to determine the action by using 'enqueue', 'dequeue' or 'register'. For example, if you add a css stylesheet with action register as key, this will result in the stylesheet being registered using wp_enqueue_style.

**context (string)**
Allows you to specifically define the context in which something needs to be enqueued using 'admin', 'login', 'both'. Only on the admin side, on the front-end or on both?  You can also add your assets to the login page.

**exclude (array)**
Accepts an array with admin page hooks, such as edit.php on which you want to exclude the enqueueing of admin scripts and styles

**include (array)**
Accepts an array with admin page hooks, suck as edit.php on which you want to include the enqueueing of admin scripts and styles

### Create instance
Create a new instance of the WP_Enqueue class with your assets array as argument.

            $enqueue = new MT_WP_Enqueue($assets);
