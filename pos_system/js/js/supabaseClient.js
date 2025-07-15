// js/supabaseClient.js
import { createClient } from 'https://cdn.jsdelivr.net/npm/@supabase/supabase-js/+esm';

const SUPABASE_URL = 'https://pvojevkazwrkjdwqjgrw.supabase.co';
const SUPABASE_ANON_KEY = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InB2b2pldmthendya2pkd3FqZ3J3Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NTIzMzcyNTMsImV4cCI6MjA2NzkxMzI1M30.AonguMS1faIMvGJk2qDFBxsLosXuXW6j5r3HtHmccgU';

export const supabase = createClient(SUPABASE_URL, SUPABASE_ANON_KEY);
