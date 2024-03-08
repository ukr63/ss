import Index from "@/views/dashboard";
import Layout from "@/views/layouts/main";
import {Metadata} from "next";

export const metadata: Metadata = {
    title: 'test'
};

const Page = async () => {
    const props = {
        text: 'text'
    }

    return (
        <Layout>
            <Index {...props} />
        </Layout>
    )
}

export default Page;