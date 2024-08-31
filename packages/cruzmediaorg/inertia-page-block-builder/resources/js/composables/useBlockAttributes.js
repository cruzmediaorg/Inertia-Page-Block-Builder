import { computed } from 'vue';

export default function useBlockAttributes(props) {
    const blockStyle = computed(() => ({
        paddingTop: props.paddingTop || '0px',
        paddingRight: props.paddingRight || '0px',
        paddingBottom: props.paddingBottom || '0px',
        paddingLeft: props.paddingLeft || '0px',
        marginTop: props.marginTop || '0px',
        marginRight: props.marginRight || '0px',
        marginBottom: props.marginBottom || '0px',
        marginLeft: props.marginLeft || '0px',
    }));

    return {
        blockStyle,
    };
}