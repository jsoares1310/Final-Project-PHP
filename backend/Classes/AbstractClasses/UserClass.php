<?php
    abstract class User {
        private int $uid;
        protected string $fname;
        protected string $lname;
        protected string $email; 
        protected string $phone;
        
        public function __construct(int $uid, string $fname, string $lname, string $email, string $phone) {
            $this->uid = $uid;
            $this->fname = $fname;
            $this->lname = $lname;
            $this->email = $email;
            $this->phone = $phone;
        }
        public function get_uid() : int {
            return $this->uid;
        }
        public function set_fname(string $newfname) : void {
            $this->fname = $newfname;
        }
        public function get_fname() : string {
            return $this->fname;
        }
        public function set_lname(string $newlname) : void {
            $this->lname = $newlname;
        }
        public function get_lname() : string {
            return $this->lname;
        }
        public function set_email(string $newemail) : void {
            $this->email = $newemail;
        }
        public function get_email() : string {
            return $this->email;
        }
        public function set_phone(string $newphone) : void {
            $this->phone = $newphone;
        }
        public function get_phone() : string {
            return $this->phone;
        }
    }


?>