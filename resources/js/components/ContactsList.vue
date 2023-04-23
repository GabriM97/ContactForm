<script setup>
    import ContactCard from './ContactCard.vue';
    import axios from 'axios';
    import { ref } from 'vue';

    const contacts = ref([]);

    axios.get('/api/contacts', {})
        .then(res => {
            contacts.value = res.data;
            console.log(contacts.value);
        }).catch (err => {
            console.log(err.response);
        });
</script>

<template>
    <!-- loop though all contacts -->
    <ContactCard v-for="contact in contacts" :key="contact.id" :contact="contact"></ContactCard>
</template>