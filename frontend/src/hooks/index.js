import { computed } from "vue";

const useToLocaleDateString = (dateString) => {
    return computed(() => {
        const event = new Date(dateString);
            
        return event.toLocaleDateString(undefined, {  
            year: 'numeric', 
            month: 'short', 
            day: 'numeric' 
        });
    });
}

const useFilterRecord = (key, heystack, needle) => {
    return heystack.filter((el) => {
        return el[key] == needle;
    })[0];
}

export default {
    useToLocaleDateString,
    useFilterRecord
};