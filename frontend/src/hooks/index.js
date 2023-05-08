import { computed } from "vue";

export default function useDate(dateString) {
    const toLocaleDateString = computed(() => {
        const event = new Date(dateString);
            
        return event.toLocaleDateString(undefined, {  
            year: 'numeric', 
            month: 'short', 
            day: 'numeric' 
        });
    });

    return {
        toLocaleDateString
    };
}