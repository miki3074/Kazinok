import fetch from "unfetch";
import VueCookies from "vue-cookie";

export default class GraphQL {
    query(url, obj) {
        return new Promise((resolve, reject) => {
            let headers =  { "Content-Type": "application/json" };

            if (this.getCustomerCookieToken()) {
                headers["Authorization"] = "Bearer " + this.getCustomerCookieToken();
            }

            resolve(fetch(url, {
                method: "POST",
                headers: headers,
                body: JSON.stringify(obj)
            })
                .then(res => res.json())
                .then(res => {
                    if (res.data && !res.errors) {
                        return res.data;
                    } else {
                        throw new Error(JSON.stringify(res.errors));
                    }
                }));
        });
    }

    getMinesGame() {
        return new Promise((resolve, reject) => {
            this.query('/graphql/mines', {
                query: `mutation {
                        MinesGame {
                            success
                            hash
                            bet
                            bomb
                            selected_bombs
                            active_path
                        }
                    }`
            })
                .then(data => resolve(data))
                .catch(error => reject(error));
        });
    }

    getCustomerCookieToken() {
        return VueCookies.get("token");
    }

    getCreateUserVK() {
        return new Promise((resolve, reject) => {
            this.query('/graphql', {
                query: `mutation CreateUser {
                        CreateUserVK {
                            url
                        }
                    }`
            })
                .then(data => resolve(data))
                .catch(error => reject(error));
        });
    }

    getLoginUser(obj) {
        return new Promise((resolve, reject) => {
            this.query('/graphql', {
                query: `mutation($username: String!, $password: String!) {
                            LoginUser(username: $username, password: $password) {
                                token
                              }
                    }`,
                variables: obj
            })
                .then(data => resolve(data))
                .catch(error => reject(error));
        });
    }

    getUser() {
        return new Promise((resolve, reject) => {
            this.query('/graphql/user', {
                query: `query {
                            User {
                                id
                                username
                                balance
                                is_vk
                                vk_id
                                vk_username
                                vk_only
                                is_tg
                                tg_id
                                tg_username
                                tg_only
                                is_admin
                                is_moder
                                is_promocoder
                                bonus_use
                                ban
                                ban_reason
                                wallet_qiwi
                                wallet_fk
                                wallet_card
                                wallet_yoomoney
                                wallet_piastrix
                                rang_points
                                current_rang
                              }
                            }`
            })
                .then(data => resolve(data))
                .catch(error => reject(error));
        });
    }

    getResetPassword(obj) {
        return new Promise((resolve, reject) => {
            this.query('/graphql/user', {
                query: `mutation($old_password: String!, $new_password: String!) {
                            ResetPassword(old_password: $old_password, new_password: $new_password) {
                                success
                              }
                    }`,
                variables: obj
            })
                .then(data => resolve(data))
                .catch(error => reject(error));
        });
    }

    getLinkUserVK() {
        return new Promise((resolve, reject) => {
            this.query('/graphql/user', {
                query: `mutation {
                        LinkUserVK {
                            url
                        }
                    }`
            })
                .then(data => resolve(data))
                .catch(error => reject(error));
        });
    }

    getGameHashGuest() {
        return new Promise((resolve, reject) => {
            this.query('/graphql', {
                query: `mutation {
                        GetHashGuest {
                            hash
                            hid
                        }
                    }`
            })
                .then(data => resolve(data))
                .catch(error => reject(error));
        });
    }

    getGameHash() {
        return new Promise((resolve, reject) => {
            this.query('/graphql/game', {
                query: `mutation {
                        GetHash {
                            hash
                            hid
                        }
                    }`
            })
                .then(data => resolve(data))
                .catch(error => reject(error));
        });
    }

    getGameHistory(obj) {
        return new Promise((resolve, reject) => {
            this.query('/graphql', {
                query: `query($id: Int!) {
  GetGame(id: $id) {
    id
    bet
    game
    chance
    win
    type
    dice {
      hid
      hash
      salt1
      salt2
      random
      string
    }
    mine
  }
}`,
                variables: obj
            })
                .then(data => resolve(data))
                .catch(error => reject(error));
        });
    }

    getError(error) {
        error = error.toString();
        error = error.replace(/Error: /g, '');
        error = JSON.parse(error);
        error = error[0];

        return error;
    }
}
