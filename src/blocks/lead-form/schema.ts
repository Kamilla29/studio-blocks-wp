import { z } from 'zod';
import { __ } from '@wordpress/i18n';

export const leadSchema = z.object({
  name: z.string().trim().min(2, __('Please enter your name.', 'studio-blocks')).max(120),
  email: z.string().trim().email(__('Please enter a valid email address.', 'studio-blocks')).max(190),
  company: z.string().trim().max(160).optional().or(z.literal('')),
  message: z
    .string()
    .trim()
    .min(20, __('Please add a little more detail.', 'studio-blocks'))
    .max(1000, __('Please keep the message under 1000 characters.', 'studio-blocks')),
  website: z.string().max(0).optional().or(z.literal('')),
});

export type LeadFormValues = z.infer<typeof leadSchema>;
