import { computed } from 'vue';

export default function useBlockAttributes(props) {
    const blockStyle = computed(() => {
        const appendPxIfNeeded = (value) => {
            if (value === undefined) return '0px';
            return value.toString().endsWith('px') ? value : `${value}px`;
        };

        const padding = [
            appendPxIfNeeded(props.padding?.top),
            appendPxIfNeeded(props.padding?.right),
            appendPxIfNeeded(props.padding?.bottom),
            appendPxIfNeeded(props.padding?.left)
        ].join(' ');

        const margin = [
            appendPxIfNeeded(props.margin?.top),
            appendPxIfNeeded(props.margin?.right),
            appendPxIfNeeded(props.margin?.bottom),
            appendPxIfNeeded(props.margin?.left)
        ].join(' ');

        return {
            padding,
            margin,
        };
    });

    return {
        blockStyle,
    };
}