<script setup>
    import ContactForm from './components/ContactForm.vue';
    import ContactsList from './components/ContactsList.vue';
    import LoginForm from './components/LoginForm.vue';
    import RegisterForm from './components/RegisterForm.vue';
    import TopBar from './components/TopBar.vue';
    import axios from 'axios';
    import { ref, reactive, onMounted, watch } from 'vue';

    const App = {
        user: reactive({
            loggedIn: false,
            details: {},
            
            populate: async function () {
                axios.get('/api/user/details', {})
                .then(res => {
                    App.user.details = res.data.data;
                }).catch(err => {
                    App.errorMessage.value = err.message;
                })
            },

            reset: function () {
                App.user.loggedIn = false;
                App.user.details = {};
            }
        }),

        errorMessage: ref(''),

        accessToken: reactive({
            token: '',
            expiryDate: new Date,

            reset: function () {
                App.accessToken.create('', new Date);
                delete axios.defaults.headers.common['Authorization'];
                localStorage.removeItem('accessToken');
            },

            create: function (newToken, newExpiryDate) {
                App.accessToken.token = newToken;
                App.accessToken.expiryDate = new Date(newExpiryDate);
                axios.defaults.headers.common['Authorization'] = 'Bearer ' + App.accessToken.token

                // storing the token in the localStorage is not safe. It should be stored into Vuex storage instead.
                localStorage.setItem('accessToken', JSON.stringify({
                    token: App.accessToken.token,
                    expiryDate: App.accessToken.expiryDate
                }));
            },
        }),
        
    };

    const contactsList = ref([]);

    const showRegisterForm = ref(false);

    const handleLogin = function (tokenData) {
        App.accessToken.create(tokenData.token, tokenData.expiryDate);
        App.user.loggedIn = true;
        App.user.populate();
        showRegisterForm.value = false;
        App.errorMessage.value = '';
    }

    const handleLogout = function (logout) {
        App.user.loggedIn = false;
        App.accessToken.reset();
        App.user.reset();
        showRegisterForm.value = false;
        App.errorMessage.value = '';
    }

    onMounted(() => {
        if (localStorage.getItem('accessToken') !== null) {
            let localAccessToken = JSON.parse(localStorage.getItem('accessToken'));
            handleLogin(localAccessToken);

            if (App.accessToken.expiryDate <= (new Date)) {
                handleLogout();
            }
        }
    });    
</script>

<template>
    <div class="relative p-1 xl:w-1/4 lg:w-1/3 md:w-2/3 sm:w-3/4 m-auto">
        <TopBar 
            @loggedOutEvent="(logout) => handleLogout(logout)"
            @errorEvent="(error) => App.errorMessage.value = error"
            :userName="App.user.details.name"
            :errorMessage="App.errorMessage"
        />
        <div class="">
            <LoginForm v-if="!App.user.loggedIn && !showRegisterForm"
                @loggedInEvent="(tokenData) => handleLogin(tokenData)"
                @showRegisterFormEvent="(show) => showRegisterForm = show"
                @errorEvent="(error) => App.errorMessage.value = error"
            />

            <RegisterForm v-if="!App.user.loggedIn && showRegisterForm"
                @registeredEvent="(tokenData) => handleLogin(tokenData)"
                @showLoginFormEvent="(hide) => showRegisterForm = hide"
                @errorEvent="(error) => App.errorMessage.value = error"
            />
 
            <ContactsList v-if="App.user.loggedIn" 
                :contacts="contactsList" />

            <ContactForm v-if="App.user.loggedIn" 
                @contactSentEvent="(newContact) => contactsList.value.push(newContact)" 
                @errorEvent="(error) => App.errorMessage.value = error"
                :userEmail="App.user.details.email"
            />
        </div>
    </div>
</template>