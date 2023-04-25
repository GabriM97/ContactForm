<script setup>
    import { ref } from 'vue';

    const props = defineProps({
        contact: Object
    })

</script>

<template>
    <div class="flex flex-col">
        <div>Name: {{ contact.name }}</div>
        <div>Surname: {{ contact.surname }}</div>
        <div>Email: {{ contact.email }}</div>
        <div>Message: {{ contact.message }}</div>
    </div>
</template>