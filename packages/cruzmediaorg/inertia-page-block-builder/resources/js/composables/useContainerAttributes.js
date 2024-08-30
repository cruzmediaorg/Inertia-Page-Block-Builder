import { reactive } from 'vue';

export default function useContainerAttributes() {
  const defaultAttributes = {
    backgroundColor: '#ffffff',
    paddingTop: '0px',
    paddingRight: '0px',
    paddingBottom: '0px',
    paddingLeft: '0px',
    marginTop: '0px',
    marginRight: '0px',
    marginBottom: '0px',
    marginLeft: '0px',
    borderRadius: '0px',
    hideOnMobile: false,
    blockGap: '0px',
    flexDirectionDesktop: 'row',
    flexDirectionMobile: 'column',
  };

  const createAttributes = (initialAttributes = {}) => {
    return reactive({
      ...defaultAttributes,
      ...initialAttributes,
    });
  };

  const resetAttributes = (attributes) => {
    Object.assign(attributes, defaultAttributes);
  };

  return {
    defaultAttributes,
    createAttributes,
    resetAttributes,
  };
}