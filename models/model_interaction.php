<?php

namespace adapt\interactions{
    
    /* Prevent Direct Access */
    defined('ADAPT_STARTED') or die;
    
    class model_interaction extends \adapt\model{
        
        public function __construct($id = null){
            parent::__construct('interaction', $id);
        }
        
        public function remove_data($key){
            $children = $this->get();
            
            foreach($children as $child){
                if ($child instanceof \adapt\model && $child->table_name == 'interaction_data'){
                    if ($child->data_key == $key){
                        $child->delete();
                    }
                }
            }
                
            
            return null;
        }
        
        public function data($key, $value = null){
            $children = $this->get();
            
            if (is_null($value)){
                foreach($children as $child){
                    if ($child instanceof \adapt\model && $child->table_name == 'interaction_data'){
                        if ($child->data_key == $key){
                            if ($child->is_serialized == 'Yes'){
                                return unserialize($child->data);
                            }else{
                                return $child->data;
                            }
                        }
                    }
                }
                
            }else{
                foreach($children as $child){
                    if ($child instanceof \adapt\model && $child->table_name == 'interaction_data'){
                        if ($child->data_key == $key){
                            if (is_object($value) || is_array($value)){
                                $child->data = serialize($value);
                                $child->is_serialized = 'Yes';
                            }else{
                                $child->is_serialized = 'No';
                                $child->data = $value;
                            }
                            
                            return null;
                        }
                    }
                }
                
                $setting = new model_interaction_data();
                $setting->data_key = $key;
                if (is_object($value) || is_array($value)){
                    $setting->data = serialize($value);
                    $setting->is_serialized = 'Yes';
                }else{
                    $setting->is_serialized = 'No';
                    $setting->data = $value;
                }
                
                $this->add($setting);
            }
            
            return null;
        }
        
        /* Over-ride the initialiser to auto load children */
        public function initialise(){
            /* We must initialise first! */
            parent::initialise();
            
            /* We need to limit what we auto load */
            $this->_auto_load_only_tables = array(
                'interaction_data'
            );
            
            /* Switch on auto loading */
            $this->_auto_load_children = true;
        }
    }
}
