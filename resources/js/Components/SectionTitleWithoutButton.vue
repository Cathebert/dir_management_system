<script setup>
import { mdiCog } from '@mdi/js'
import { useSlots, computed } from 'vue'
import BaseIcon from '@/Components/BaseIcon.vue'
import BaseButton from '@/Components/BaseButton.vue'
import IconRounded from '@/Components/IconRounded.vue'

defineProps({

    title: {
        type: String,
        required: true
    },
    icon: {
        type: String,
        default: null
    },
    main: Boolean
})

const hasSlot = computed(() => useSlots().default)
</script>

<template>
    <section :class="{ 'pt-6': !main }" class="mb-6 flex items-center justify-between">
        <div class="flex items-center justify-start">
            <IconRounded v-if="icon && main" :icon="icon" type="light" class="mr-3" bg />

            <h1 :class="main ? 'text-3xl' : 'text-2xl'" class="leading-tight">
                {{ title }}
            </h1>
        </div>

    </section>
</template>
