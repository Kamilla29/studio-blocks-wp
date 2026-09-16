import { InspectorControls, RichText, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

type LeadFormAttributes = {
  eyebrow: string;
  heading: string;
  buttonLabel: string;
};

type EditProps = {
  attributes: LeadFormAttributes;
  setAttributes: (attributes: Partial<LeadFormAttributes>) => void;
};

export default function Edit({ attributes, setAttributes }: EditProps) {
  const blockProps = useBlockProps({ className: 'studio-lead-form-block studio-lead-form-block--editor' });

  return (
    <>
      <InspectorControls>
        <PanelBody title={__('Form settings', 'studio-blocks')} initialOpen>
          <TextControl
            label={__('Button label', 'studio-blocks')}
            value={attributes.buttonLabel}
            onChange={(buttonLabel) => setAttributes({ buttonLabel })}
          />
        </PanelBody>
      </InspectorControls>

      <section {...blockProps}>
        <div className="studio-lead-form-block__copy">
          <RichText
            tagName="p"
            className="studio-lead-form-block__eyebrow"
            value={attributes.eyebrow}
            allowedFormats={[]}
            onChange={(eyebrow) => setAttributes({ eyebrow })}
          />
          <RichText
            tagName="h2"
            className="studio-lead-form-block__heading"
            value={attributes.heading}
            allowedFormats={[]}
            onChange={(heading) => setAttributes({ heading })}
          />
        </div>

        <div className="studio-lead-form studio-lead-form--preview" aria-hidden="true">
          <div className="studio-lead-form__row">
            <label>
              {__('Name', 'studio-blocks')}
              <input type="text" disabled />
            </label>
            <label>
              {__('Email', 'studio-blocks')}
              <input type="email" disabled />
            </label>
          </div>
          <label>
            {__('Message', 'studio-blocks')}
            <textarea rows={5} disabled />
          </label>
          <button type="button" disabled>{attributes.buttonLabel}</button>
        </div>

        <p className="studio-lead-form-block__editor-note">
          {__('Frontend validation and submission are handled by the React view script.', 'studio-blocks')}
        </p>
      </section>
    </>
  );
}
