const { addFilter } = wp.hooks;
const { createHigherOrderComponent, compose } = wp.compose;
const { withSelect } = wp.data;
const { Fragment } = wp.element;
const { InspectorControls, MediaUpload, MediaUploadCheck } = wp.blockEditor || wp.editor;
const { PanelBody, TextControl, Button, SelectControl } = wp.components;
const { __ } = wp.i18n;
const { withState } = wp.compose;

const textPresetBlocks = [
  'core/paragraph',
  'core/heading',
  'core/list',
  'core/list-item',
  'core/quote',
  'core/pullquote',
  'core/verse',
  'core/preformatted',
  'core/button',
  'core/post-title',
  'core/post-excerpt',
  'core/post-navigation-link',
  'core/navigation',
];
//change this styles to match your theme's font styles
const textPresetOptions = [
    { label: 'Default', value: '' },
    { label: 'Primary', value: 'is-style-primary' },
    { label: 'Secondary', value: 'is-style-secondary' },
    { label: 'Tertiary', value: 'is-style-tertiary' },
    { label: 'Quaternary', value: 'is-style-quaternary' },
    { label: 'Quinary', value: 'is-style-quinary' },
    { label: 'Timeline Heading', value: 'is-style-timeline-heading' },
    { label: 'Eyebrow', value: 'is-style-eyebrow' },
    { label: 'Button Text', value: 'is-style-button-text' }
];

// 1. Add attributes to blocks
addFilter(
    'blocks.registerBlockType',
    'verbovancouver/add-text-preset-attribute',
    ( settings, name ) => {
        if ( ! textPresetBlocks.includes( name ) ) {
            return settings;
        }

        return {
            ...settings,
            attributes: {
                ...settings.attributes,
                textPreset: {
                    type: 'string',
                    default: '',
                },
            },
        };
    }
);

// 2. Add custom control to sidebar
const withTextPresetControl = createHigherOrderComponent( ( BlockEdit ) => {
    return ( props ) => {
        if ( ! textPresetBlocks.includes( props.name ) ) {
            return <BlockEdit { ...props } />;
        }

        const { attributes, setAttributes } = props;
        const { textPreset } = attributes;

        return (
            <Fragment>
                <BlockEdit { ...props } />
                <InspectorControls>
                    <PanelBody title="Text Preset">
                        <SelectControl
                            label="Select a text style preset"
                            value={ textPreset }
                            options={ textPresetOptions }
                            onChange={ ( value ) => setAttributes( { textPreset: value } ) }
                        />
                    </PanelBody>
                </InspectorControls>
            </Fragment>
        );
    };
}, 'withTextPresetControl' );

addFilter( 'editor.BlockEdit', 'verbovancouver/add-text-preset-control', withTextPresetControl );

// 3. Add custom class to block
const withTextPresetClass = createHigherOrderComponent( ( BlockListBlock ) => {
    return ( props ) => {
        if ( ! textPresetBlocks.includes( props.name ) ) {
            return <BlockListBlock { ...props } />;
        }

        const { attributes, className } = props;
        const { textPreset } = attributes;

        return <BlockListBlock { ...props } className={ [ className, textPreset ].filter( Boolean ).join( ' ' ) } />;
    };
}, 'withTextPresetClass' );

addFilter( 'editor.BlockListBlock', 'verbovancouver/add-text-preset-class', withTextPresetClass );

// Add the class to the block's save output
const addTextPresetSaveClass = (extraProps, blockType, attributes) => {
  if (!textPresetBlocks.includes(blockType.name) || !attributes.textPreset) {
    return extraProps;
  }

  // Only add the class if it's not already present to avoid duplicates
  const classes = new Set((extraProps.className || '').split(' '));
  classes.add(attributes.textPreset);
  
  return {
    ...extraProps,
    className: Array.from(classes).filter(Boolean).join(' ')
  };
};

addFilter('blocks.getSaveContent.extraProps', 'verbovancouver/add-text-preset-save-class', addTextPresetSaveClass);

// Ensure the class is included in the block's edit function
const addTextPresetEditClass = (settings, name) => {
  if (!textPresetBlocks.includes(name)) {
    return settings;
  }

  const originalGetEditWrapperProps = settings.getEditWrapperProps;
  
  return {
    ...settings,
    getEditWrapperProps(attributes) {
      const props = originalGetEditWrapperProps ? originalGetEditWrapperProps(attributes) : {};
      
      if (!attributes.textPreset) {
        return props;
      }

      return {
        ...props,
        className: [props.className, attributes.textPreset].filter(Boolean).join(' ')
      };
    }
  };
};

addFilter('blocks.registerBlockType', 'verbovancouver/add-text-preset-edit-class', addTextPresetEditClass);

// ===== CUSTOM ATTRIBUTES FEATURE (restricted to textPresetBlocks) =====

// Add custom attributes to whitelisted blocks only
const addCustomAttributes = (settings, name) => {
  if (!textPresetBlocks.includes(name)) return settings;

  return {
    ...settings,
    attributes: {
      ...settings.attributes,
      customAttributes: {
        type: 'string',
        default: '',
      }
    }
  };
};

// Create the custom controls (only for whitelisted blocks)
const withCustomAttributesControls = createHigherOrderComponent((BlockEdit) => {
  return (props) => {
    const { attributes, setAttributes, isSelected, name } = props;
    if (!textPresetBlocks.includes(name)) {
      return <BlockEdit {...props} />;
    }

    return (
      <Fragment>
        <BlockEdit {...props} />
        {isSelected && (
          <InspectorControls>
            <PanelBody 
              title="Custom Attributes" 
              initialOpen={false}
              className="custom-attributes-panel"
            >
              <TextControl
                label="Custom HTML Attributes"
                help="Add any HTML attributes (e.g., data-speed='1.5' data-animation='fade')"
                value={attributes.customAttributes || ''}
                onChange={(value) => setAttributes({ customAttributes: value })}
              />
            </PanelBody>
          </InspectorControls>
        )}
      </Fragment>
    );
  };
}, 'withCustomAttributesControls');

// Add the custom attributes to the block's wrapper (save & edit)
const addCustomAttributesToBlock = (props, blockType, attributes) => {
  if (!textPresetBlocks.includes(blockType.name)) return props;
  if (!attributes.customAttributes) return props;

  // Parse the custom attributes string into an object
  const parser = new DOMParser();
  const doc = parser.parseFromString(`<div ${attributes.customAttributes}></div>`, 'text/html');
  const element = doc.body.firstChild;
  
  if (element) {
    Array.from(element.attributes).forEach(attr => {
      // Skip class attribute to avoid conflicts
      if (attr.name !== 'class') {
        props[attr.name] = attr.value;
      } else {
        // Handle class separately to merge with existing classes
        const existingClasses = props.className ? props.className.split(' ') : [];
        const newClasses = attr.value.split(' ');
        const combinedClasses = [...new Set([...existingClasses, ...newClasses])].join(' ').trim();
        props.className = combinedClasses;
      }
    });
  }

  return props;
};

// Ensure attributes are available in the editor (only for whitelisted blocks)
const filterCustomAttributes = (attributes, blockType) => {
  if (!textPresetBlocks.includes(blockType.name)) return attributes;
  if (!attributes.customAttributes) return attributes;

  const parser = new DOMParser();
  const doc = parser.parseFromString(`<div ${attributes.customAttributes}></div>`, 'text/html');
  const element = doc.body.firstChild;
  
  if (element) {
    Array.from(element.attributes).forEach(attr => {
      if (attr.name !== 'class') {
        attributes[attr.name] = attr.value;
      } else {
        const existingClasses = attributes.className ? attributes.className.split(' ') : [];
        const newClasses = attr.value.split(' ');
        const combinedClasses = [...new Set([...existingClasses, ...newClasses])].join(' ').trim();
        attributes.className = combinedClasses;
      }
    });
  }

  return attributes;
};

// Apply the filters
addFilter('blocks.registerBlockType', 'verbovancouver/custom-attributes', addCustomAttributes);
addFilter('editor.BlockEdit', 'verbovancouver/custom-attributes-controls', withCustomAttributesControls);
addFilter('blocks.getSaveContent.extraProps', 'verbovancouver/custom-attributes-save', addCustomAttributesToBlock);
addFilter('blocks.getBlockAttributes', 'verbovancouver/custom-attributes-edit', filterCustomAttributes);

// Add some basic styles for the custom attributes panel
const style = document.createElement('style');
style.textContent = `
  .custom-attributes-panel .components-base-control {
    margin-bottom: 16px;
  }
  .custom-attributes-panel .components-base-control__help {
    margin-top: 4px;
    font-style: italic;
    color: #757575;
  }
`;
document.head.appendChild(style);

wp.domReady( function() {
  
	wp.blocks.unregisterBlockStyle(
		'core/button',
		[ 'outline', 'squared', 'fill' ]
	);

	wp.blocks.registerBlockStyle(
		'core/group',
		[
			{
				name: 'block-section',
				label: 'Page Section',
			}
		]
	);


	wp.blocks.registerBlockStyle(
		'core/cover',
		[
			{
				name: 'hero',
				label: 'Hero Section',
			}
		]
	);
	wp.blocks.registerBlockStyle(
		'core/button',
		[
			{
				name: 'secondary-btn',
				label: 'Secondary Btn',
			}
		]
	);

	wp.blocks.registerBlockStyle(
		'core/image',
		[
			{
				name: 'square',
				label: 'Square',
			}
		]
	);
  
} );
  
wp.hooks.addFilter(
  'blocks.registerBlockType',
  'verbovancouver/alter-core-blocks',
  function( settings ) {
    
    if ( settings.name === 'core/button' ) {
      // console.dir( settings );
    }
    
    return settings;
    
  }
);