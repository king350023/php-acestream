<?php

namespace Sokil\ACEPlayer;

class Player
{    
    private $_config = array(
        'debug'                     => false,
        'media'                     => null,
        'height'                    => '470px',
        'notInstalledPlayerMessage' => null,
    );
    
    /**
     * 
     * @param array $config params of config equivalent to setter names
     */
    public function __construct(array $config = array()) 
    {
        foreach($config as $name => $value) {
            $this->{$name} = $value;
        }
    }
    
    public function __set($name, $value)
    {
        $methodName = 'set' . $name;
        if(method_exists($this, $methodName)) {
            call_user_func(array($this, $methodName), $value);
        } else {
            $this->_config[$name] = $value;
        }
    }
    
    public function setNotInstalledPlayerMessage($message)
    {
        $this->_config['notInstalledPlayerMessage'] = $message;
        return $this;
    }
    
    public function setDebug($debug) {
        $this->_config['debug'] = (bool) $debug;
        return $this;
    }
    
    /**
     * 
     * @param string|array $url May be URL or array(url => ..., name => ...);
     * @return \ACEPlayer
     */
    public function setMedia($url)
    {
        $this->_config['media'] = $url;
        
        return $this;
    }
    
    public function setHeight($height)
    {
        $this->_config['height'] = $height;
        
        if(is_numeric($height)) {
            $this->_config['height'] .= 'px';
        }
        
        return $this;
    }
    
    public function render()
    {
        ob_start();
        
        $config = array();
        
        /**
         * Prepare content definition block
         */        
        if($this->_config['media']) {
            $config['media'] = $this->_config['media'];
        }
        
            
        // add scripts
        if($this->_config['debug']) {
            $config['debug'] = true;
        }
        
        // add html code
        ?>
        <div id="acestream-player" style="height:<?php echo $this->_config['height']; ?>; text-align: center;">
            <?php echo $this->_config['notInstalledPlayerMessage']; ?>
        </div>
        <script type="text/javascript">
        var player = new AceStreamPlayer(document.getElementById("acestream-player"), <?php echo json_encode($config); ?>);
        </script>
        <?php
        
        return ob_get_clean();
    }
    
    public function __toString()
    {
        return $this->render();
    }
}
