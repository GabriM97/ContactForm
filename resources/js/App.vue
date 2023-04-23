<script setup>
    import ContactForm from './components/ContactForm.vue';
    import ContactsList from './components/ContactsList.vue';
    import LoginForm from './components/LoginForm.vue';
    import RegisterForm from './components/RegisterForm.vue';
    import TopBar from './components/TopBar.vue';
    import axios from 'axios';
    import { ref, reactive, onMounted } from 'vue';

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
            },

            create: function (newToken, newExpiryDate) {
                App.accessToken.token = newToken;
                App.accessToken.expiryDate = newExpiryDate;
                axios.defaults.headers.common['Authorization'] = 'Bearer ' + App.accessToken.token
            },
        }),
        
    };

    const showRegisterForm = ref(false);

    const handleLogin = function (tokenData) {
        App.accessToken.create(tokenData.token, tokenData.expiryDate);
        App.user.loggedIn = true;
        App.user.populate();
    }

    const handleLogout = function (logout) {
        App.user.loggedIn = false;
        App.accessToken.reset();
        App.user.reset();
    }

    onMounted(() => {
        console.log('checking if access token expired');

        if (App.accessToken.expiryDate <= (new Date)) {
            App.accessToken.reset();
        }

        // App.errorMessage.value = '';
        // showRegisterForm.value = false;
    });
</script>

<template>
    <TopBar 
        @loggedOutEvent="(logout) => handleLogout(logout)"
        @errorEvent="(error) => App.errorMessage.value = error"

        :userName="App.user.details.name" 
        :errorMessage="App.errorMessage"
    />

    <LoginForm v-if="!App.user.loggedIn && !showRegisterForm"
        @loggedInEvent="(tokenData) => handleLogin(tokenData)"
        @showRegisterFormEvent="(show) => showRegisterForm = show"
        @errorEvent="(error) => App.errorMessage.value = error"
    />
<!-- 
    <RegisterForm v-if="!App.user.loggedIn && showRegisterForm" 
        @loggedInEvent="login => App.user.loggedIn = login"
        @showLoginFormEvent="(hide) => showRegisterForm = hide"
    />

    <ContactsList v-if="App.user.loggedIn.value" />

    <ContactForm v-if="App.user.loggedIn.value" @formSentEvent="contactFormSent" />
-->
</template>