<?php
    require_once __DIR__."/../app/database/db.class.php";
    require_once __DIR__ . '/user.class.php';

    class Service
    {
        function quackoviPoDatumu($username)
        {
            $db = DB::getConnection();

            $st = $db->prepare('SELECT quack,date FROM dz2_quacks INNER JOIN dz2_users WHERE dz2_quacks.id_user = dz2_users.id AND username = :username');
            $st->execute(['username' => $username]);

            $quackovi = [];
            // dohvati quackove po datumu
            while($row = $st->fetch())
            {
                $quackovi[] = array( 'quack' => $row['quack'], 'date' => $row['date'] ) ;
            }
            // sortiranje
            $price = array();
            foreach ($quackovi as $key => $row)
            {
                $price[$key] = $row['date'];
            }
            array_multisort($price, SORT_DESC, $quackovi);

            return $quackovi;
        }

        function getId($username)
        {
          $db = DB::getConnection();

          $st = $db->prepare('SELECT id FROM dz2_users WHERE username = :username');
          $st->execute(['username' => $username]);

          $id;
          if( $row = $st->fetch() )
          {
            $id = $row['id'];
          }
          return $id;
        }

        function getUsername($id)
        {
          $db = DB::getConnection();

          $st = $db->prepare('SELECT username FROM dz2_users WHERE id = :id');
          $st->execute(['id' => $id]);

          $username;
          if($row = $st->fetch())
          {
              $username = $row["username"];
          }
          return $username;
        }

        function allFollowers($username)
        {
            // dohvati sve follow-ere za dani username
            $db = DB::getConnection();

            $id = $this->getId($username);

            $st = $db->prepare('SELECT username FROM dz2_users INNER JOIN dz2_follows WHERE dz2_users.id = dz2_follows.id_user AND id_followed_user = :id');
            $st->execute(['id' => $id]);

            $followers = [];
            while($row = $st->fetch())
            {
                $followers[] = array( $row["username"]);
            }
            return $followers;
        }

        function allFollowing($username)
        {
            // dohvati sve koje follow-je za dani username
            $db = DB::getConnection();

            $id = $this->getId($username);

            $st = $db->prepare('SELECT username FROM dz2_users INNER JOIN dz2_follows WHERE dz2_users.id = dz2_follows.id_followed_user AND id_user = :id');
            $st->execute(['id' => $id]);
            $following = [];
            while($row = $st->fetch())
            {
                $following[] = array( $row["username"]);
            }
            return $following;
        }

        function spremiQuack($username, $quack, $date)
        {
             $db = DB::getConnection();

             // odredimo id_usera da spremimo njegov indeks uz quack
             $id_user = $this->getId($username);

             // dohvatimo sve quackove da vidimo jel se ponavlja
             $st = $db->prepare('SELECT quack,date FROM dz2_quacks WHERE id_user = :id_user');
             $st->execute(['id_user' => $id_user]);
             $flag = 0;
             while($row = $st->fetch())
             {
                 if ( $quack == $row["quack"] && $date == $row["date"] )
                 {
                   $flag = 1;
                 }
            }
            if(! $flag)
            {
              $st = $db->prepare('INSERT INTO dz2_quacks( id_user, quack , date) VALUES ( :id_user, :quack, :date)' );
              $st->execute(array ('id_user'=>$id_user, 'quack'=>$quack, 'date'=>$date) );
            }
            return 1;
        }

        function quackoviPoDatumuFollowing($list)
        {
            $db = DB::getConnection();
            $quackovi = [];

            foreach ( $list as $username )
            {
              $username = $username[0];
              $st = $db->prepare('SELECT quack,date FROM dz2_quacks INNER JOIN dz2_users WHERE dz2_quacks.id_user = dz2_users.id AND username = :username');
              $st->execute(['username' => $username]);

              // dohvati quackove po datumu
              while($row = $st->fetch())
              {
                  $quackovi[] = array('username' => $username, 'quack' => $row['quack'], 'date' => $row['date'] ) ;
              }
          }
          // sortiranje
          $sort = array();
          foreach ($quackovi as $key => $row)
          {
              $sort[$key] = $row['date'];
          }
          array_multisort($sort, SORT_DESC, $quackovi);

            return $quackovi;
        }

        function obrisiFollower($username, $follow)
        {

            $db = DB::getConnection();

            $id_followed_user = $this->getId($username);
            $username = $follow;
            $id_user = $this->getId($username);

            $st = $db->prepare("DELETE FROM dz2_follows WHERE id_user = :id_user AND id_followed_user = :id_followed_user");
            $st->execute(["id_user" => $id_user, "id_followed_user" => $id_followed_user]);
            return 1;
        }

        function obrisiFollowing($username, $follow)
        {

            $db = DB::getConnection();

            $id_user = $this->getId($username);
            $username = $follow;
            $id_followed_user = $this->getId($username);

            $st = $db->prepare("DELETE FROM dz2_follows WHERE id_user = :id_user AND id_followed_user = :id_followed_user");
            $st->execute(["id_user" => $id_user, "id_followed_user" => $id_followed_user]);
            return 1;
        }

        function newFollowing( $username, $follow )
        {
             $db = DB::getConnection();

             $id_user = $this->getId($username);
             $username = $follow;
             $id_followed_user = $this->getId($username);

             // prvo provjeri jel ga već prati:
             $st = $db->prepare('SELECT id_followed_user FROM dz2_follows WHERE id_user = :id_user AND id_followed_user = :id_followed_user');
             $st->execute(['id_user' => $id_user, 'id_followed_user' => $id_followed_user]);
             if( $row = $st->fetch() )
             {
               // postoji već follower
             }
             else{

               if ( !( $id_user == $id_followed_user ))
               {
                 $st = $db->prepare('INSERT INTO dz2_follows(id_user, id_followed_user) VALUES
                                    (:id_user, :id_followed_user)');
                 $st->execute(array('id_user'=>$id_user, 'id_followed_user'=>$id_followed_user));
               }
               $id = $db->lastInsertId();
               return $id;
           }
        }

        function quackoviPoUsernameu($username)
        {
            $db = DB::getConnection();

            $st = $db->prepare('SELECT * FROM dz2_quacks');
            $st->execute();

            $user = $username;
            $quackovi = [];
            while($row = $st->fetch())
            {
              $quack = $row['quack'];
              $position = strpos($quack, '@'.$user); //strpos
              if ($position !== false) {
                //postoji @username
                $username = $this->getUsername($row['id_user']);
                $quackovi[] = array( 'username' => $username, 'quack' => $row['quack'], 'date' => $row['date'] ) ;
                }
              }
            return $quackovi;
        }
        function quackoviPoHashtagu($tag)
        {
            $db = DB::getConnection();

            $st = $db->prepare('SELECT * FROM dz2_quacks');
            $st->execute();

            $quackovi = [];
            while($row = $st->fetch())
            {
              $quack = $row['quack'];
              $position = stripos($quack, $tag); //strpos
              if ($position !== false) {
                //postoji #hashtag
                $username = $this->getUsername($row['id_user']);
                $quackovi[] = array( 'username' => $username, 'quack' => $row['quack'], 'date' => $row['date'] ) ;
                }
              }
            return $quackovi;
        }
    }
?>