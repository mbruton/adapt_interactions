<?php

namespace adapt\interactions{
    
    /* Prevent Direct Access */
    defined('ADAPT_STARTED') or die;
    
    class bundle_adapt_interactions extends \adapt\bundle{

    public function __construct($data){
            parent::__construct('adapt_interactions', $data);
        }
        
        public function boot(){
            if (parent::boot()){
                
                /* Extend base and add interactions */
                \adapt\base::extend("interaction", 
                    function($_this, $interaction_type){
                        $type = new model_interaction_type();
                        if (!$type->load_by_name($interaction_type)){
                            $_this->error("Unknown interaction type '{$interaction_type}'");
                            return false;
                        }
                        
                        if (!$_this->session->is_logged_in){
                            $_this->error("Not logged in");
                            return false;
                        }
                        
                        $interaction = new model_interaction();
                        $interaction->interaction_type_id = $type->interaction_type_id;
                        $interaction->user_id = $_this->session->user->user_id;
                        if (!$interaction->save()){
                            $_this->error("Unable to create interaction");
                            return false;
                        }
                        
                        return $interaction;
                    }
                );
                
                return true;
            }
            
            return false;
        }
    }
}
