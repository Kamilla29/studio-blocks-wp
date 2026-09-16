import type { ReactNode } from 'react';
import { createRoot, useMemo } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { QueryClient, QueryClientProvider, useMutation } from '@tanstack/react-query';
import { zodResolver } from '@hookform/resolvers/zod';
import { useForm } from 'react-hook-form';
import { leadSchema, type LeadFormValues } from './schema';

const queryClient = new QueryClient({
  defaultOptions: {
    mutations: { retry: false },
  },
});

type FormConfig = {
  restUrl: string;
  buttonLabel: string;
  successMessage: string;
};

async function submitLead(restUrl: string, values: LeadFormValues) {
  const response = await fetch(restUrl, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify(values),
  });

  const body = await response.json().catch(() => ({}));

  if (!response.ok) {
    throw new Error(body?.message || __('The request could not be sent.', 'studio-blocks'));
  }

  return body;
}

function LeadForm({ config }: { config: FormConfig }) {
  const formId = useMemo(() => `studio-lead-${Math.random().toString(36).slice(2, 9)}`, []);
  const {
    register,
    handleSubmit,
    reset,
    formState: { errors },
  } = useForm<LeadFormValues>({
    resolver: zodResolver(leadSchema),
    defaultValues: {
      name: '',
      email: '',
      company: '',
      message: '',
      website: '',
    },
  });

  const mutation = useMutation({
    mutationFn: (values: LeadFormValues) => submitLead(config.restUrl, values),
    onSuccess: () => reset(),
  });

  const onSubmit = handleSubmit((values) => mutation.mutate(values));
  const statusMessage = mutation.isSuccess
    ? config.successMessage
    : mutation.error instanceof Error
      ? mutation.error.message
      : '';

  return (
    <form className="studio-lead-form" noValidate onSubmit={onSubmit}>
      <div className="studio-lead-form__row">
        <Field
          label={__('Name', 'studio-blocks')}
          id={`${formId}-name`}
          error={errors.name?.message}
        >
          <input
            id={`${formId}-name`}
            type="text"
            autoComplete="name"
            aria-invalid={Boolean(errors.name)}
            aria-describedby={errors.name ? `${formId}-name-error` : undefined}
            {...register('name')}
          />
        </Field>

        <Field
          label={__('Email', 'studio-blocks')}
          id={`${formId}-email`}
          error={errors.email?.message}
        >
          <input
            id={`${formId}-email`}
            type="email"
            autoComplete="email"
            aria-invalid={Boolean(errors.email)}
            aria-describedby={errors.email ? `${formId}-email-error` : undefined}
            {...register('email')}
          />
        </Field>
      </div>

      <Field
        label={__('Company (optional)', 'studio-blocks')}
        id={`${formId}-company`}
        error={errors.company?.message}
      >
        <input
          id={`${formId}-company`}
          type="text"
          autoComplete="organization"
          aria-invalid={Boolean(errors.company)}
          aria-describedby={errors.company ? `${formId}-company-error` : undefined}
          {...register('company')}
        />
      </Field>

      <Field
        label={__('Project brief', 'studio-blocks')}
        id={`${formId}-message`}
        error={errors.message?.message}
      >
        <textarea
          id={`${formId}-message`}
          rows={6}
          aria-invalid={Boolean(errors.message)}
          aria-describedby={errors.message ? `${formId}-message-error` : undefined}
          {...register('message')}
        />
      </Field>

      <div className="studio-lead-form__honeypot" aria-hidden="true">
        <label htmlFor={`${formId}-website`}>{__('Website', 'studio-blocks')}</label>
        <input id={`${formId}-website`} type="text" tabIndex={-1} autoComplete="off" {...register('website')} />
      </div>

      <div className="studio-lead-form__actions">
        <button type="submit" disabled={mutation.isPending}>
          {mutation.isPending ? __('Sending…', 'studio-blocks') : config.buttonLabel}
        </button>
        <p
          className={`studio-lead-form__status ${mutation.isSuccess ? 'is-success' : mutation.isError ? 'is-error' : ''}`}
          role="status"
          aria-live="polite"
        >
          {statusMessage}
        </p>
      </div>
    </form>
  );
}

function Field({
  label,
  id,
  error,
  children,
}: {
  label: string;
  id: string;
  error?: string;
  children: ReactNode;
}) {
  return (
    <div className="studio-lead-form__field">
      <label htmlFor={id}>{label}</label>
      {children}
      {error ? (
        <p className="studio-lead-form__error" id={`${id}-error`}>
          {error}
        </p>
      ) : null}
    </div>
  );
}

function mountForms() {
  document.querySelectorAll<HTMLElement>('.studio-lead-form-root').forEach((node) => {
    const restUrl = node.dataset.restUrl;
    if (!restUrl || node.dataset.mounted === 'true') return;

    node.dataset.mounted = 'true';

    const config: FormConfig = {
      restUrl,
      buttonLabel: node.dataset.buttonLabel || __('Send request', 'studio-blocks'),
      successMessage: node.dataset.successMessage || __('Thank you. Your request has been received.', 'studio-blocks'),
    };

    createRoot(node).render(
      <QueryClientProvider client={queryClient}>
        <LeadForm config={config} />
      </QueryClientProvider>,
    );
  });
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', mountForms, { once: true });
} else {
  mountForms();
}
