import type { CSSProperties } from 'react';
import { InspectorControls, RichText, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, RangeControl, SelectControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

type ServiceGridAttributes = {
  heading: string;
  intro: string;
  columns: number;
  limit: number;
};

type EditProps = {
  attributes: ServiceGridAttributes;
  setAttributes: (attributes: Partial<ServiceGridAttributes>) => void;
};

const placeholderServices = [
  ['Brand websites', 'Responsive commercial websites with reusable sections.'],
  ['Campaign landing pages', 'Focused conversion pages with accessible interactions.'],
  ['Content systems', 'Structured WordPress content that editors can manage safely.'],
];

export default function Edit({ attributes, setAttributes }: EditProps) {
  const blockProps = useBlockProps({ className: 'studio-service-grid studio-service-grid--editor' });

  return (
    <>
      <InspectorControls>
        <PanelBody title={__('Grid settings', 'studio-blocks')} initialOpen>
          <SelectControl
            label={__('Columns', 'studio-blocks')}
            value={String(attributes.columns)}
            options={[
              { label: __('Two', 'studio-blocks'), value: '2' },
              { label: __('Three', 'studio-blocks'), value: '3' },
              { label: __('Four', 'studio-blocks'), value: '4' },
            ]}
            onChange={(value) => setAttributes({ columns: Number(value) })}
          />
          <RangeControl
            label={__('Maximum services', 'studio-blocks')}
            value={attributes.limit}
            min={1}
            max={12}
            onChange={(value) => setAttributes({ limit: value ?? 6 })}
          />
        </PanelBody>
      </InspectorControls>

      <section {...blockProps}>
        <RichText
          tagName="h2"
          className="studio-service-grid__heading"
          value={attributes.heading}
          allowedFormats={[]}
          onChange={(heading) => setAttributes({ heading })}
          placeholder={__('Section heading…', 'studio-blocks')}
        />
        <RichText
          tagName="p"
          className="studio-service-grid__intro"
          value={attributes.intro}
          allowedFormats={[]}
          onChange={(intro) => setAttributes({ intro })}
          placeholder={__('Short introduction…', 'studio-blocks')}
        />

        <div
          className="studio-service-grid__items"
          style={{ '--studio-columns': attributes.columns } as CSSProperties}
          aria-hidden="true"
        >
          {placeholderServices.map(([title, text]) => (
            <article className="studio-service-card" key={title}>
              <span className="studio-service-card__eyebrow">{__('Preview', 'studio-blocks')}</span>
              <h3>{title}</h3>
              <p>{text}</p>
            </article>
          ))}
        </div>

        <p className="studio-service-grid__editor-note">
          {__('The frontend is rendered in PHP from Studio Service posts.', 'studio-blocks')}
        </p>
      </section>
    </>
  );
}
