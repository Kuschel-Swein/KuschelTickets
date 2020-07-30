<?php
use kt\system\Utils;
use kt\data\user\User;
use kt\data\user\UserList;
use kt\system\UserUtils;
use kt\data\user\group\Group;
use kt\data\user\group\GroupList;
use kt\system\CRSF;
use kt\system\exception\PageNotFoundException;
use kt\system\exception\AccessDeniedException;
use kt\system\KuschelTickets;

/**
 * 
 * Accounts Admin Page Handler
 * 
 */
if(isset($parameters['add'])) {
    $subpage = "add";

    $errors = array(
        "username" => false,
        "password" => false,
        "password_confirm" => false,
        "email" => false,
        "token" => false,
        "group" => false
    );
    $success = false;
    if(isset($parameters['submit'])) {
        if(isset($parameters['CRSF']) && !empty($parameters['CRSF'])) {
            if(CRSF::validate($parameters['CRSF'])) {
                if(isset($parameters['username']) && !empty($parameters['username'])) {
                    if(isset($parameters['email']) && !empty($parameters['email'])) {
                        if(filter_var($parameters['email'], FILTER_VALIDATE_EMAIL)) {
                            if(isset($parameters['password']) && !empty($parameters['password'])) {
                                if(isset($parameters['password_confirm']) && !empty($parameters['password_confirm'])) {
                                    if($parameters['password'] == $parameters['password_confirm']) {
                                        if(!UserUtils::exists($parameters['username'], "username")) {
                                            if(!UserUtils::exists($parameters['email'], "email")) {
                                                if(isset($parameters['group']) && !empty($parameters['group'])) {
                                                    $username = strip_tags($parameters['username']);
                                                    $email = strip_tags($parameters['email']);
                                                    $password = password_hash($parameters['password'], PASSWORD_BCRYPT);
                                                    $usergroup = strip_tags($parameters['group']);
                                                    $token = UserUtils::generateToken();
                                                    User::create(array(
                                                        "username" => $username,
                                                        "password" => $password,
                                                        "email" => $email,
                                                        "token" => $token,
                                                        "userGroup" => $usergroup,
                                                        "banned" => 0,
                                                        "password_reset" => 0
                                                    ));
                                                    $success = true;
                                                } else {
                                                    $errors['group'] = "Bitte wähle eine Benutzergruppe.";
                                                }
                                            } else {
                                                $errors['email'] = "Diese E-Mail Adresse ist bereits vergeben.";
                                            }
                                        } else {
                                            $errors['username'] = "Dieser Benutzername ist bereits vergeben.";
                                        }
                                    } else {
                                        $errors['password_confirm'] = "Dieses Passwort stimmt nicht mit dem obrigen überein.";
                                    }
                                } else {
                                    $errors['password_confirm'] = "Bitte bestätige dein Passwort.";
                                }
                            } else {
                                $errors['password'] = "Bitte gib ein Passwort an.";
                            }
                        } else {
                            $errors['email'] = "Diese E-Mail Adresse ist keine gültige E-Mail Adresse.";
                        }
                    } else {
                        $errors['email'] = "Bitte gib eine E-Mail Adresse an.";
                    }
                } else {
                    $errors['username'] = "Bitte gib einen Benutzernamen an.";
                }
            } else {
                $errors['token'] = "Deine Sitzung ist leider abgelaufen, bitte lade die Seite neu.";
            }
        } else {
            $errors['token'] = "Deine Sitzung ist leider abgelaufen, bitte lade die Seite neu.";
        }
    }

    $site = array(
        "success" => $success,
        "site" => $subpage,
        "errors" => $errors,
        "usergroups" => new GroupList()
    );

} else if(isset($parameters['edit'])) {
    $subpage = "edit";
    if(empty($parameters['edit'])) {
        throw new PageNotFoundException("Diese Seite wurde leider nicht gefunden.");
    }
    $account = new User($parameters['edit']);
    if(!$account->userID) {
        throw new PageNotFoundException("Diese Seite wurde leider nicht gefunden.");
    }

    $errors = array(
        "username" => false,
        "password" => false,
        "password_confirm" => false,
        "email" => false,
        "token" => false,
        "group" => false
    );
    $success = false;
    if(isset($parameters['submit'])) {
        if(isset($parameters['CRSF']) && !empty($parameters['CRSF'])) {
            if(CRSF::validate($parameters['CRSF'])) {
                if(isset($parameters['username']) && !empty($parameters['username'])) {
                    if(isset($parameters['email']) && !empty($parameters['email'])) {
                        if(filter_var($parameters['email'], FILTER_VALIDATE_EMAIL)) {                                
                            if(!UserUtils::exists($parameters['username'], "username") || $parameters['username'] == $account->username) {
                                if(!UserUtils::exists($parameters['email'], "email") || $parameters['email'] == $account->email) {
                                    if(isset($parameters['group']) && !empty($parameters['group'])) {
                                        if(isset($parameters['password']) && !empty($parameters['password'])) {
                                            if(isset($parameters['password_confirm']) && !empty($parameters['password_confirm'])) {
                                                if($parameters['password'] == $parameters['password_confirm']) {
                                                    $username = strip_tags($parameters['username']);
                                                    $email = strip_tags($parameters['email']);
                                                    $password = password_hash($parameters['password'], PASSWORD_BCRYPT);
                                                    $usergroup = strip_tags($parameters['group']);
                                                    $token = UserUtils::generateToken();
                                                    $account->update(array(
                                                        "username" => $username,
                                                        "password" => $password,
                                                        "email" => $email,
                                                        "token" => $token,
                                                        "userGroup" => $usergroup
                                                    ));
                                                    $success = true;
                                                } else {
                                                    $errors['password_confirm'] = "Dieses Passwort stimmt nicht mit dem obrigen überein.";
                                                }
                                            } else {
                                                $errors['password_confirm'] = "Bitte bestätige dein Passwort.";
                                            }
                                        } else {
                                            $username = strip_tags($parameters['username']);
                                            $email = strip_tags($parameters['email']);
                                            $usergroup = strip_tags($parameters['group']);
                                            $token = UserUtils::generateToken();
                                            $account->update(array(
                                                "username" => $username,
                                                "email" => $email,
                                                "token" => $token,
                                                "userGroup" => $usergroup
                                            ));
                                            $success = true;
                                        }
                                    } else {
                                        $errors['group'] = "Bitte wähle eine Benutzergruppe.";
                                    }
                                } else {
                                    $errors['email'] = "Diese E-Mail Adresse ist bereits vergeben.";
                                }
                            } else {
                                $errors['username'] = "Dieser Benutzername ist bereits vergeben.";
                            }
                        } else {
                            $errors['email'] = "Diese E-Mail Adresse ist keine gültige E-Mail Adresse.";
                        }
                    } else {
                        $errors['email'] = "Bitte gib eine E-Mail Adresse an.";
                    }
                } else {
                    $errors['username'] = "Bitte gib einen Benutzernamen an.";
                }
            } else {
                $errors['token'] = "Deine Sitzung ist leider abgelaufen, bitte lade die Seite neu.";
            }
        } else {
            $errors['token'] = "Deine Sitzung ist leider abgelaufen, bitte lade die Seite neu.";
        }
    }

    $site = array(
        "success" => $success,
        "site" => $subpage,
        "errors" => $errors,
        "usergroups" => new GroupList(),
        "edituser" => $account
    );
} else if(isset($parameters['ban'])) {
    $subpage = "ban";

    if(empty($parameters['ban'])) {
        throw new PageNotFoundException("Diese Seite wurde leider nicht gefunden.");
    }
    $account = new User($parameters['ban']);
    if(!$account->userID) {
        throw new PageNotFoundException("Diese Seite wurde leider nicht gefunden.");
    }

    if($account->hasPermission("admin.bypass.bannable")) {
        throw new AccessDeniedException("Diese Seite wurde leider nicht gefunden.");
    }

    $errors = array(
        "token" => false,
        "reason" => false
    );
    $success = false;

    if(isset($parameters['submit'])) {
        if(isset($parameters['CRSF']) && !empty($parameters['CRSF'])) {
            if(CRSF::validate($parameters['CRSF'])) {
                if($account->banned == 1) {
                    if(isset($parameters['unban'])) {
                        $account->update(array(
                            "banned" => 0,
                            "banreason" => null
                        ));
                        $success = "Dieser Account wurde erfolgreich entsperrt.";
                    }
                } else {
                    if(isset($parameters['ban'])) {
                        if(isset($parameters['reason']) && !empty($parameters['reason'])) {
                            $reason = Utils::purify($parameters['reason']);
                            $account->update(array(
                                "banned" => 1,
                                "banreason" => $reason
                            ));
                            $success = "Dieser Account wurde erfolgreich gesperrt.";
                        } else {
                            $errors['reason'] = "Bitte gib eine Begründung für diese Sperre ein.";
                        }
                        
                    }
                }
            } else {
                $errors['token'] = "Deine Sitzung ist leider abgelaufen, bitte lade die Seite neu.";
            }
        } else {
            $errors['token'] = "Deine Sitzung ist leider abgelaufen, bitte lade die Seite neu.";
        }
    }
    
    $site = array(
        "errors" => $errors,
        "banuser" => $account,
        "site" => $subpage,
        "success" => $success
    );
    
} else {
    $subpage = "index";

    $site = array(
        "accounts" => new UserList(),
        "site" => $subpage
    );
}




